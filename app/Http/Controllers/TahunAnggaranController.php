<?php

namespace App\Http\Controllers;

use App\Http\Requests\TahunAnggaranRequest;
use App\Models\Pengajuan;
use App\Models\TahunAnggaran;
use App\Models\Scopes\TahunAnggaranScope;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class TahunAnggaranController extends Controller
{
    /**
     * Ganti tahun anggaran yang aktif di sesi (semua role, dinamis tanpa login ulang).
     */
    public function pilih(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'tahun' => ['required', 'integer'],
        ]);

        $ada = TahunAnggaran::query()->where('tahun', $validated['tahun'])->exists();

        if (! $ada) {
            toast()->error('Gagal', 'Tahun anggaran tidak ditemukan.');

            return back();
        }

        $request->session()->put('tahun_anggaran', (int) $validated['tahun']);
        toast()->success('Berhasil', 'Tahun anggaran diubah ke '.$validated['tahun'].'.');

        return back();
    }

    private function data(): \Illuminate\Http\JsonResponse
    {
        $data = TahunAnggaran::query()->orderByDesc('tahun');

        return DataTables::of($data)
            ->addColumn('label_tampil', fn ($row) => $row->label_tampil)
            ->addColumn('status', function ($row) {
                return $row->is_terkunci
                    ? '<span class="badge bg-warning text-dark">Terkunci</span>'
                    : '<span class="badge bg-success">Terbuka</span>';
            })
            ->addColumn('action', function ($row) {
                $navActionStart = '<nav class="breadcrumb-container d-inline-block" aria-label="breadcrumb"><ul class="breadcrumb pt-0">';
                $navActionEnd = '</ul></nav>';

                $edit = "<li class='breadcrumb-item'><a href='".route('tahun-anggaran.edit', $row->id)."' title='Edit Data'
                        class='fw-bold text-success'>Edit</a></li>";

                $delete = "<li class='breadcrumb-item'><a href='".route('tahun-anggaran.destroy', $row->id)."' data-confirm-delete='true'
                        title='Hapus Data' class='fw-bold text-danger'>Delete</a></li>";

                return $navActionStart.$edit.$delete.$navActionEnd;
            })
            ->rawColumns(['status', 'action'])
            ->toJson();
    }

    public function index()
    {
        confirmDelete('Hapus Data', 'Yakin ingin menghapus tahun anggaran ini?');
        if (request()->ajax()) {
            return $this->data();
        }

        return view('pages.tahun-anggaran.index');
    }

    public function create()
    {
        return view('pages.tahun-anggaran.create');
    }

    public function store(TahunAnggaranRequest $request): RedirectResponse
    {
        try {
            DB::beginTransaction();
            TahunAnggaran::query()->create($request->validated());
            DB::commit();
            toast()->success('Yeeayy !!', 'Tahun anggaran berhasil disimpan');

            return redirect()->route('tahun-anggaran.index');
        } catch (\Throwable $th) {
            DB::rollBack();
            toast()->error('Oppss !!', $th->getMessage());

            return back()->withInput();
        }
    }

    public function edit(TahunAnggaran $tahun_anggaran)
    {
        return view('pages.tahun-anggaran.create', ['tahunAnggaran' => $tahun_anggaran]);
    }

    public function update(TahunAnggaranRequest $request, TahunAnggaran $tahun_anggaran): RedirectResponse
    {
        try {
            DB::beginTransaction();
            $tahun_anggaran->update($request->validated());
            DB::commit();
            toast()->success('Yeeayy !!', 'Tahun anggaran berhasil disimpan');

            return redirect()->route('tahun-anggaran.index');
        } catch (\Throwable $th) {
            DB::rollBack();
            toast()->error('Oppss !!', $th->getMessage());

            return back()->withInput();
        }
    }

    public function destroy(TahunAnggaran $tahun_anggaran): RedirectResponse
    {
        $dipakai = Pengajuan::query()
            ->withoutGlobalScope(TahunAnggaranScope::class)
            ->where('tahun_anggaran', $tahun_anggaran->tahun)
            ->exists();

        if ($dipakai) {
            toast()->error('Gagal', 'Tahun anggaran sudah dipakai pada data pengajuan dan tidak dapat dihapus.');

            return redirect()->route('tahun-anggaran.index');
        }

        try {
            DB::beginTransaction();
            $tahun_anggaran->delete();
            DB::commit();
            toast()->success('Yeeayy !!', 'Tahun anggaran berhasil dihapus');
        } catch (\Throwable $th) {
            DB::rollBack();
            toast()->error('Oppss !!', $th->getMessage());
        }

        return redirect()->route('tahun-anggaran.index');
    }
}
