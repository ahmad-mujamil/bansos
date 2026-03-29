<?php

namespace App\Http\Controllers;

use App\Enums\PengajuanStatus;
use App\Http\Requests\BastRequest;
use App\Models\Bast;
use App\Models\Pengajuan;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class BastController extends Controller
{
    private function data()
    {
        $data = Bast::query()->with(['pengajuan.verifikasiPengajuan', 'user'])->latest();

        return DataTables::of($data)
            ->editColumn('tanggal', fn ($row) => $row->tanggal->format('d-m-Y'))
            ->addColumn('kode_pengajuan', fn ($row) => $row->pengajuan?->kode_pengajuan ?? '-')
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
            ->rawColumns(['action'])
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
        $pengajuan = Pengajuan::query()
            ->whereHas('verifikasiPengajuan')
            ->where('status', PengajuanStatus::DISETUJUI->value)
            ->latest()
            ->get();

        return view('pages.bast.create', compact('pengajuan'));
    }

    public function store(BastRequest $request)
    {
        try {
            $validated = $request->validated();
            DB::beginTransaction();

            $bast = Bast::query()->create([
                'pengajuan_id' => $validated['pengajuan_id'],
                'nomor' => $validated['nomor'],
                'tanggal' => \Carbon\Carbon::createFromFormat('d-m-Y', $validated['tanggal'])->format('Y-m-d'),
                'penerima' => $validated['penerima'],
                'user_id' => auth()->id(),
            ]);

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
        $pengajuan = Pengajuan::query()
            ->whereRelation('verifikasiPengajuan', ['status' => 'disetujui'])
            ->latest()
            ->get();

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
            toast()->error('Oppss !!', $th->getMessage());

            return redirect()->route('bast.index');
        }
    }
}
