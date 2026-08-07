<?php

namespace App\Http\Controllers;

use App\Enums\PengajuanStatus;
use App\Http\Requests\BastRequest;
use App\Models\Bast;
use App\Models\Pengajuan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class BastController extends Controller
{
    private function data()
    {
        $opdId = Auth::user()->opd_id;

        $data = Bast::query()
            ->with(['pengajuan.verifikasiPengajuan', 'user'])
            // Hanya BAST dari pengajuan milik OPD user yang login (super/admin tanpa OPD: semua).
            ->when($opdId !== null, fn ($q) => $q->whereHas('pengajuan', fn ($p) => $p->where('opd_id', $opdId)))
            ->latest();

        return DataTables::of($data)
            ->editColumn('tanggal', fn ($row) => $row->tanggal->format('d-m-Y'))
            ->addColumn('kode_pengajuan', fn ($row) => $row->pengajuan?->kode_pengajuan ?? '-')
            ->addColumn('jenis_bantuan', function ($row) {
                $kategori = $row->pengajuan?->kategori_pengajuan;
                $warna = match ($kategori) {
                    \App\Enums\JenisPengajuan::SUBSIDI_BUNGA => 'bg-warning text-dark',
                    \App\Enums\JenisPengajuan::BANTUAN_KELOMPOK => 'bg-primary',
                    \App\Enums\JenisPengajuan::HIBAH => 'bg-info text-dark',
                    \App\Enums\JenisPengajuan::BANSOS => 'bg-success',
                    default => 'bg-secondary',
                };

                return '<span class="badge '.$warna.'">'.e($kategori?->getDescription() ?? '-').'</span>';
            })
            ->addColumn('nilai_rekomendasi', function ($row) {
                $nilai = $row->pengajuan?->verifikasiPengajuan?->nilai_rekomendasi;

                return $nilai !== null ? 'Rp '.number_format($nilai, 0, ',', '.') : '-';
            })
            ->addColumn('action', function ($row) {
                $navActionStart = '<nav class="breadcrumb-container d-inline-block" aria-label="breadcrumb"><ul class="breadcrumb pt-0">';
                $navActionEnd = '</ul></nav>';

                $show = "<li class='breadcrumb-item'><a href='".route('bast.show', $row->id)."' title='Detail Data' class='fw-bold text-primary'>Detail</a></li>";
                $edit = "<li class='breadcrumb-item'><a href='".route('bast.edit', $row->id)."' title='Edit Data' class='fw-bold text-success'>Edit</a></li>";
                $delete = "<li class='breadcrumb-item'><a href='".route('bast.destroy', $row->id)."' data-confirm-delete='true' title='Hapus Data' class='fw-bold text-danger'>Delete</a></li>";

                return $navActionStart.$show.$edit.$delete.$navActionEnd;
            })
            ->rawColumns(['jenis_bantuan', 'action'])
            ->toJson();
    }

    public function index()
    {
        confirmDelete('Delete Data', 'Are you sure you want to delete?');
        if (request()->ajax()) {
            return $this->data();
        }

        return view('pages.bast.index');
    }

    public function create()
    {
        $opdId = Auth::user()->opd_id;

        $pengajuan = $this->pengajuanSiapBast($opdId)
            ->with(['organisasi', 'details.penduduk', 'verifikasiPengajuan', 'jenisBantuan', 'desa.kecamatan'])
            ->get();

        return view('pages.bast.create', compact('pengajuan'));
    }

    /**
     * Kriteria pengajuan yang siap dibuatkan BAST — selaras dengan halaman monitoring:
     * status DISETUJUI, BA verifikasi sudah diunggah, dan belum punya BAST.
     */
    private function pengajuanSiapBast(?string $opdId)
    {
        return Pengajuan::query()
            ->where('status', PengajuanStatus::DISETUJUI->value)
            ->whereHas('verifikasiPengajuan.media', fn ($q) => $q->where('collection_name', 'ba-verifikasi'))
            ->whereDoesntHave('bast')
            ->when($opdId !== null, fn ($q) => $q->where('opd_id', $opdId))
            ->latest();
    }

    public function store(BastRequest $request)
    {
        try {
            $validated = $request->validated();
            DB::beginTransaction();

            if (Bast::query()->where('pengajuan_id', $validated['pengajuan_id'])->exists()) {
                DB::rollBack();
                toast()->error('Oppss !!', 'Pengajuan ini sudah memiliki BAST.');

                return back()->withInput();
            }

            $bast = Bast::query()->create([
                'pengajuan_id' => $validated['pengajuan_id'],
                'nomor' => $validated['nomor'],
                'tanggal' => \Carbon\Carbon::createFromFormat('d-m-Y', $validated['tanggal'])->format('Y-m-d'),
                'penerima' => $validated['penerima'],
                'user_id' => auth()->id(),
            ]);

            // Subsidi bunga: nilai rekomendasi kredit diisi saat BAST → simpan ke verifikasi.
            $pengajuanBast = Pengajuan::query()->with('verifikasiPengajuan')->find($validated['pengajuan_id']);
            if (
                $pengajuanBast?->kategori_pengajuan === \App\Enums\JenisPengajuan::SUBSIDI_BUNGA
                && ($validated['nilai_rekomendasi'] ?? null) !== null
                && $pengajuanBast->verifikasiPengajuan
            ) {
                $pengajuanBast->verifikasiPengajuan->update([
                    'nilai_rekomendasi' => $validated['nilai_rekomendasi'],
                ]);
            }

            if ($request->hasFile('dokumen')) {
                $bast->addMediaFromRequest('dokumen')->toMediaCollection('dokumen');
            }

            if ($request->hasFile('foto')) {
                foreach ($request->file('foto') as $foto) {
                    $bast->addMedia($foto)->toMediaCollection('foto');
                }
            }

            DB::commit();
            toast()->success('Yeeayy !!', 'Data berhasil disimpan');

            return redirect()->route('bast.index');
        } catch (\Throwable $th) {
            DB::rollBack();
            toast()->error('Oppss !!', $th->getMessage());

            return back()->withInput();
        }
    }

    public function show(Bast $bast)
    {
        $bast->load(['pengajuan.verifikasiPengajuan', 'user']);

        return view('pages.bast.show', compact('bast'));
    }

    public function edit(Bast $bast)
    {
        $opdId = Auth::user()->opd_id;

        // Daftar sama dengan create, tapi tetap sertakan pengajuan milik BAST ini
        // (yang sudah punya BAST) agar tetap terpilih di form.
        $pengajuan = $this->pengajuanSiapBast($opdId)
            ->orWhere('pengajuan.id', $bast->pengajuan_id)
            ->with(['organisasi', 'details.penduduk', 'verifikasiPengajuan', 'jenisBantuan', 'desa.kecamatan'])
            ->get();

        $bast->load('pengajuan.verifikasiPengajuan');

        return view('pages.bast.create', compact('bast', 'pengajuan'));
    }

    public function update(BastRequest $request, Bast $bast)
    {
        try {
            $validated = $request->validated();
            DB::beginTransaction();

            $bast->update([
                'pengajuan_id' => $validated['pengajuan_id'],
                'nomor' => $validated['nomor'],
                'tanggal' => \Carbon\Carbon::createFromFormat('d-m-Y', $validated['tanggal'])->format('Y-m-d'),
                'penerima' => $validated['penerima'],
            ]);

            // Subsidi bunga: perbarui nilai rekomendasi kredit bila diisi.
            $pengajuanBast = Pengajuan::query()->with('verifikasiPengajuan')->find($validated['pengajuan_id']);
            if (
                $pengajuanBast?->kategori_pengajuan === \App\Enums\JenisPengajuan::SUBSIDI_BUNGA
                && ($validated['nilai_rekomendasi'] ?? null) !== null
                && $pengajuanBast->verifikasiPengajuan
            ) {
                $pengajuanBast->verifikasiPengajuan->update([
                    'nilai_rekomendasi' => $validated['nilai_rekomendasi'],
                ]);
            }

            if ($request->hasFile('dokumen')) {
                $bast->addMediaFromRequest('dokumen')->toMediaCollection('dokumen');
            }

            if ($request->hasFile('foto')) {
                $bast->clearMediaCollection('foto');
                foreach ($request->file('foto') as $foto) {
                    $bast->addMedia($foto)->toMediaCollection('foto');
                }
            }

            DB::commit();
            toast()->success('Yeeayy !!', 'Data berhasil disimpan');

            return redirect()->route('bast.index');
        } catch (\Throwable $th) {
            DB::rollBack();
            toast()->error('Oppss !!', $th->getMessage());

            return back()->withInput();
        }
    }

    public function destroy(Bast $bast)
    {
        try {
            DB::beginTransaction();
            $bast->clearMediaCollection('dokumen');
            $bast->clearMediaCollection('foto');
            $bast->delete();
            DB::commit();
            toast()->success('Yeeayy !!', 'Data berhasil dihapus');

            return redirect()->route('bast.index');
        } catch (\Throwable $th) {
            DB::rollBack();
            toast()->error('Oppss !!', $th->getMessage());

            return redirect()->route('bast.index');
        }
    }
}
