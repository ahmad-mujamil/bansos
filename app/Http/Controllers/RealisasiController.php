<?php

namespace App\Http\Controllers;

use App\Http\Requests\PengajuanRealisasiRequest;
use App\Models\Pengajuan;
use App\Models\PengajuanRealisasi;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class RealisasiController extends Controller
{
    private function pengajuanBaseQuery()
    {
        $q = Pengajuan::query()
            ->whereHas('bast')
            ->with(['bast', 'realisasi', 'verifikasiPengajuan']);

        $user = auth()->user();
        if ($user->is_user()) {
            $q->where('user_id', $user->id);
        }

        return $q;
    }

    private function authorizePengajuan(Pengajuan $pengajuan): void
    {
        $user = auth()->user();
        if ($user->is_super() || $user->is_opd()) {
            return;
        }
        abort_unless($pengajuan->user_id === $user->id, 403);
    }

    private function data()
    {
        $data = $this->pengajuanBaseQuery()->latest();

        return DataTables::of($data)
            ->addColumn('bast_nomor', fn ($row) => $row->bast?->nomor ?? '-')
            ->addColumn('bast_tanggal', fn ($row) => $row->bast?->tanggal?->format('d-m-Y') ?? '-')
            ->addColumn('nilai_rekomendasi', function ($row) {
                $nilai = $row->verifikasiPengajuan?->nilai_rekomendasi;

                return $nilai !== null ? 'Rp '.number_format($nilai, 0, ',', '.') : '-';
            })
            ->addColumn('status_realisasi', function ($row) {
                return $row->realisasi
                    ? '<span class="badge bg-success">Sudah</span>'
                    : '<span class="badge bg-warning">Belum</span>';
            })
            ->addColumn('action', function ($row) {
                $url = route('realisasi.create', $row);

                return '<a href="'.$url.'" class="fw-bold text-primary">'
                    .($row->realisasi ? 'Ubah' : 'Input')
                    .'</a>';
            })
            ->rawColumns(['status_realisasi', 'action'])
            ->toJson();
    }

    public function index()
    {
        if (request()->ajax()) {
            return $this->data();
        }

        return view('pages.realisasi.index');
    }

    public function create(Pengajuan $pengajuan)
    {
        abort_unless($pengajuan->bast()->exists(), 404);
        $this->authorizePengajuan($pengajuan);
        $pengajuan->load([
            'user',
            'verifiedBy',
            'organisasi',
            'desa.kecamatan',
            'pemeriksa',
            'jenisBantuan',
            'bast.user',
            'bast.media',
            'verifikasiPengajuan',
            'realisasi',
        ]);

        return view('pages.realisasi.create', compact('pengajuan'));
    }

    public function store(PengajuanRealisasiRequest $request, Pengajuan $pengajuan)
    {
        abort_unless($pengajuan->bast()->exists(), 404);
        $this->authorizePengajuan($pengajuan);
        abort_if($pengajuan->realisasi()->exists(), 403, 'Realisasi sudah ada.');

        try {
            $validated = $request->validated();
            DB::beginTransaction();

            $tanggal = ! empty($validated['tanggal_laporan'])
                ? \Carbon\Carbon::createFromFormat('d-m-Y', $validated['tanggal_laporan'])->format('Y-m-d')
                : null;

            $realisasi = PengajuanRealisasi::query()->create([
                'pengajuan_id' => $pengajuan->id,
                'user_id' => auth()->id(),
                'tanggal_laporan' => $tanggal,
                'keterangan' => $validated['keterangan'] ?? null,
            ]);

            if ($request->hasFile('dokumen')) {
                $realisasi->addMedia($request->file('dokumen'))->toMediaCollection('laporan_kegiatan');
            }

            DB::commit();
            toast()->success('Yeeayy !!', 'Dokumentasi realisasi berhasil disimpan');

            return redirect()->route('realisasi.index');
        } catch (\Throwable $th) {
            DB::rollBack();
            toast()->error('Oppss !!', $th->getMessage());

            return back()->withInput();
        }
    }

    public function update(PengajuanRealisasiRequest $request, Pengajuan $pengajuan)
    {
        abort_unless($pengajuan->bast()->exists(), 404);
        $this->authorizePengajuan($pengajuan);
        $realisasi = $pengajuan->realisasi;
        abort_unless($realisasi, 404);

        try {
            $validated = $request->validated();
            DB::beginTransaction();

            $tanggal = ! empty($validated['tanggal_laporan'])
                ? \Carbon\Carbon::createFromFormat('d-m-Y', $validated['tanggal_laporan'])->format('Y-m-d')
                : null;

            $realisasi->update([
                'tanggal_laporan' => $tanggal,
                'keterangan' => $validated['keterangan'] ?? null,
            ]);

            if ($request->hasFile('dokumen')) {
                $realisasi->clearMediaCollection('laporan_kegiatan');
                $realisasi->addMedia($request->file('dokumen'))->toMediaCollection('laporan_kegiatan');
            }

            DB::commit();
            toast()->success('Yeeayy !!', 'Dokumentasi realisasi berhasil diperbarui');

            return redirect()->route('realisasi.index');
        } catch (\Throwable $th) {
            DB::rollBack();
            toast()->error('Oppss !!', $th->getMessage());

            return back()->withInput();
        }
    }
}
