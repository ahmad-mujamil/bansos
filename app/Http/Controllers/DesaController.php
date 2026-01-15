<?php

namespace App\Http\Controllers;

use App\Http\Requests\DesaRequest;
use App\Models\Desa;
use App\Models\Kecamatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class DesaController extends Controller
{
    private function data()
    {
        $data = Desa::query()
            ->with('kecamatan')
            ->latest();

        return DataTables::of($data)
            ->addColumn('nama_kecamatan', fn($data) => $data->kecamatan->nama ?? '-')
            ->addColumn('action', function ($data) {
                $navActionStart = '<nav class="breadcrumb-container d-inline-block" aria-label="breadcrumb"><ul class="breadcrumb pt-0">';
                $navActionEnd = "</ul></nav>";

                $delete = "<li class='breadcrumb-item'><a href='" . route('desa.destroy', $data->id) . "' data-confirm-delete='true'
                        title='Hapus Data' class='fw-bold text-danger'>Delete</a></li>";

                $edit = "<li class='breadcrumb-item'><a href='" . route('desa.edit', $data->id) . "'  title='Edit Data'
                        class='fw-bold text-success' >Edit</a></li>";

                return $navActionStart . $edit . $delete . $navActionEnd;
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

        return view('pages.desa.index');
    }

    public function create()
    {
        $kecamatans = Kecamatan::query()->orderBy('nama')->get();
        return view('pages.desa.create', compact('kecamatans'));
    }

    public function store(DesaRequest $request)
    {
        try {
            DB::beginTransaction();
            Desa::query()->create($request->validated());
            DB::commit();
            toast()->success('Yeeayy !!', 'Data berhasil disimpan');
            return redirect()->route('desa.index');
        } catch (\Throwable $th) {
            toast()->error('Oppss !!', $th->getMessage());
            return back()->withInput();
        }
    }

    public function edit(Desa $desa)
    {
        $kecamatans = Kecamatan::query()->orderBy('nama')->get();
        return view('pages.desa.create', compact('desa', 'kecamatans'));
    }

    public function update(DesaRequest $request, Desa $desa)
    {
        try {
            DB::beginTransaction();
            $desa->update($request->validated());
            DB::commit();
            toast()->success('Yeeayy !!', 'Data berhasil disimpan');
            return redirect()->route('desa.index');
        } catch (\Throwable $th) {
            toast()->error('Oppss !!', $th->getMessage());
            return back()->withInput();
        }
    }

    public function destroy(Desa $desa)
    {
        try {
            DB::beginTransaction();
            $desa->delete();
            DB::commit();
            toast()->success('Yeeayy !!', 'Data berhasil dihapus');
            return redirect()->route('desa.index');
        } catch (\Throwable $th) {
            toast()->error('Oppss !!', $th->getMessage());
            return redirect()->route('desa.index');
        }
    }
}

