<?php

namespace App\Http\Controllers;

use App\Http\Requests\JenisBantuanRequest;
use App\Models\JenisBantuan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class JenisBantuanController extends Controller
{
    private function data(): \Illuminate\Http\JsonResponse
    {
        $data = JenisBantuan::query()
            ->latest();

        return DataTables::of($data)
            ->addColumn('nama_kecamatan', fn($data) => $data->kecamatan->nama ?? '-')
            ->addColumn('action', function ($data) {
                $navActionStart = '<nav class="breadcrumb-container d-inline-block" aria-label="breadcrumb"><ul class="breadcrumb pt-0">';
                $navActionEnd = "</ul></nav>";

                $delete = "<li class='breadcrumb-item'><a href='" . route('jenis-bantuan.destroy', $data->id) . "' data-confirm-delete='true'
                        title='Hapus Data' class='fw-bold text-danger'>Delete</a></li>";

                $edit = "<li class='breadcrumb-item'><a href='" . route('jenis-bantuan.edit', $data->id) . "'  title='Edit Data'
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

        return view('pages.jenis-bantuan.index');
    }

    public function create()
    {
        return view('pages.jenis-bantuan.create');
    }

    public function store(JenisBantuanRequest $request): ?\Illuminate\Http\RedirectResponse
    {
        try {
            DB::beginTransaction();
            JenisBantuan::query()->create($request->validated());
            DB::commit();
            toast()->success('Yeeayy !!', 'Data berhasil disimpan');
            return redirect()->route('jenis-bantuan.index');
        } catch (\Throwable $th) {
            toast()->error('Oppss !!', $th->getMessage());
            return back()->withInput();
        }
    }

    public function edit(JenisBantuan $jenisBantuan)
    {

        return view('pages.jenis-bantuan.create', compact('jenisBantuan'));
    }

    public function update(JenisBantuanRequest $request, JenisBantuan $jenisBantuan): ?\Illuminate\Http\RedirectResponse
    {
        try {
            DB::beginTransaction();
            $jenisBantuan->update($request->validated());
            DB::commit();
            toast()->success('Yeeayy !!', 'Data berhasil disimpan');
            return redirect()->route('jenis-bantuan.index');
        } catch (\Throwable $th) {
            toast()->error('Oppss !!', $th->getMessage());
            return back()->withInput();
        }
    }

    public function destroy(JenisBantuan $jenisBantuan): ?\Illuminate\Http\RedirectResponse
    {
        try {
            DB::beginTransaction();
            $jenisBantuan->delete();
            DB::commit();
            toast()->success('Yeeayy !!', 'Data berhasil dihapus');
            return redirect()->route('jenis-bantuan.index');
        } catch (\Throwable $th) {
            toast()->error('Oppss !!', $th->getMessage());
            return redirect()->route('jenis-bantuan.index');
        }
    }
}

