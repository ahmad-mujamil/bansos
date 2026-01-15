<?php

namespace App\Http\Controllers;

use App\Http\Requests\KecamatanRequest;
use App\Models\Kecamatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class KecamatanController extends Controller
{
    private function data()
    {
        $data = Kecamatan::query()
            ->latest();

        return DataTables::of($data)
            ->addColumn('action', function ($data) {
                $navActionStart = '<nav class="breadcrumb-container d-inline-block" aria-label="breadcrumb"><ul class="breadcrumb pt-0">';
                $navActionEnd = "</ul></nav>";

                $delete = "<li class='breadcrumb-item'><a href='" . route('kecamatan.destroy', $data->id) . "' data-confirm-delete='true'
                        title='Hapus Data' class='fw-bold text-danger'>Delete</a></li>";

                $edit = "<li class='breadcrumb-item'><a href='" . route('kecamatan.edit', $data->id) . "'  title='Edit Data'
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

        return view('pages.kecamatan.index');
    }

    public function create()
    {
        return view('pages.kecamatan.create');
    }

    public function store(KecamatanRequest $request)
    {
        try {
            DB::beginTransaction();
            Kecamatan::query()->create($request->validated());
            DB::commit();
            toast()->success('Yeeayy !!', 'Data berhasil disimpan');
            return redirect()->route('kecamatan.index');
        } catch (\Throwable $th) {
            toast()->error('Oppss !!', $th->getMessage());
            return back()->withInput();
        }
    }

    public function edit(Kecamatan $kecamatan)
    {
        return view('pages.kecamatan.create', compact('kecamatan'));
    }

    public function update(KecamatanRequest $request, Kecamatan $kecamatan)
    {
        try {
            DB::beginTransaction();
            $kecamatan->update($request->validated());
            DB::commit();
            toast()->success('Yeeayy !!', 'Data berhasil disimpan');
            return redirect()->route('kecamatan.index');
        } catch (\Throwable $th) {
            toast()->error('Oppss !!', $th->getMessage());
            return back()->withInput();
        }
    }

    public function destroy(Kecamatan $kecamatan)
    {
        try {
            DB::beginTransaction();
            $kecamatan->delete();
            DB::commit();
            toast()->success('Yeeayy !!', 'Data berhasil dihapus');
            return redirect()->route('kecamatan.index');
        } catch (\Throwable $th) {
            toast()->error('Oppss !!', $th->getMessage());
            return redirect()->route('kecamatan.index');
        }
    }
}

