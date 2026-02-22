<?php

namespace App\Http\Controllers;

use App\Http\Requests\PendudukRequest;
use App\Models\Penduduk;
use App\Models\Kecamatan;
use App\Models\Desa;
use App\Enums\JenisKelamin;
use App\Enums\StatusPerkawinan;
use App\Enums\LevelDesil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class PendudukController extends Controller
{
    private function data(): \Illuminate\Http\JsonResponse
    {
        $data = Penduduk::query()
            ->with(['kecamatan', 'desa'])
            ->latest();

        return DataTables::of($data)
            ->addColumn('action', function ($data) {
                $navActionStart = '<nav class="breadcrumb-container d-inline-block" aria-label="breadcrumb"><ul class="breadcrumb pt-0">';
                $navActionEnd = "</ul></nav>";

                $delete = "<li class='breadcrumb-item'><a href='" . route('penduduk.destroy', $data->id) . "' data-confirm-delete='true'
                        title='Hapus Data' class='fw-bold text-danger'>Delete</a></li>";

                $edit = "<li class='breadcrumb-item'><a href='" . route('penduduk.edit', $data->id) . "'  title='Edit Data'
                        class='fw-bold text-success' >Edit</a></li>";

                return $navActionStart . $edit . $delete . $navActionEnd;
            })
            ->editColumn('jk', function ($data) {
                return $data->jk->getDescription();
            })
            ->editColumn('level_desil', function ($data) {
                return $data->level_desil->getDescription();
            })
            ->rawColumns(['action'])
            ->toJson();
    }

    public function index()
    {
        confirmDelete("Delete Data", "Are you sure you want to delete?");
        if (request()->ajax()) {
            return $this->data();
        }

        return view('pages.penduduk.index');
    }

    public function create()
    {
        $jenis_kelamin = JenisKelamin::cases();
        $status_perkawinan = StatusPerkawinan::cases();
        $level_desil = LevelDesil::cases();

        return view('pages.penduduk.create', compact( 'jenis_kelamin', 'status_perkawinan', 'level_desil'));
    }

    public function store(PendudukRequest $request): ?\Illuminate\Http\RedirectResponse
    {
        try {
            DB::beginTransaction();
            Penduduk::query()->create($request->validated());
            DB::commit();
            toast()->success('Yeeayy !!', 'Data berhasil disimpan');
            return redirect()->route('penduduk.index');
        } catch (\Throwable $th) {
            toast()->error('Oppss !!', $th->getMessage());
            return back()->withInput();
        }
    }

    public function edit(Penduduk $penduduk)
    {
        $jenis_kelamin = JenisKelamin::cases();
        $status_perkawinan = StatusPerkawinan::cases();
        $level_desil = LevelDesil::cases();

        return view('pages.penduduk.create', compact( 'jenis_kelamin', 'status_perkawinan', 'level_desil','penduduk'));
    }

    public function update(PendudukRequest $request, Penduduk $penduduk): ?\Illuminate\Http\RedirectResponse
    {
        try {
            DB::beginTransaction();
            $penduduk->update($request->validated());
            DB::commit();
            toast()->success('Yeeayy !!', 'Data berhasil disimpan');
            return redirect()->route('penduduk.index');
        } catch (\Throwable $th) {
            toast()->error('Oppss !!', $th->getMessage());
            return back()->withInput();
        }
    }

    public function destroy(Penduduk $penduduk): ?\Illuminate\Http\RedirectResponse
    {
        try {
            DB::beginTransaction();
            $penduduk->delete();
            DB::commit();
            toast()->success('Yeeayy !!', 'Data berhasil dihapus');
            return redirect()->route('penduduk.index');
        } catch (\Throwable $th) {
            toast()->error('Oppss !!', $th->getMessage());
            return redirect()->route('penduduk.index');
        }
    }
}
