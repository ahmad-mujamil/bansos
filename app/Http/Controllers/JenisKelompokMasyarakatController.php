<?php

namespace App\Http\Controllers;

use App\Http\Requests\JenisKelompokMasyarakatRequest;
use App\Models\JenisKelompokMasyarakat;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class JenisKelompokMasyarakatController extends Controller
{
    private function data(): \Illuminate\Http\JsonResponse
    {
        $data = JenisKelompokMasyarakat::query()->latest();

        return DataTables::of($data)
            ->addColumn('action', function ($data) {
                $navActionStart = '<nav class="breadcrumb-container d-inline-block" aria-label="breadcrumb"><ul class="breadcrumb pt-0">';
                $navActionEnd = '</ul></nav>';

                $delete = "<li class='breadcrumb-item'><a href='" . route('jenis-kelompok-masyarakat.destroy', $data->id) . "' data-confirm-delete='true'
                        title='Hapus Data' class='fw-bold text-danger'>Delete</a></li>";

                $edit = "<li class='breadcrumb-item'><a href='" . route('jenis-kelompok-masyarakat.edit', $data->id) . "' title='Edit Data'
                        class='fw-bold text-success'>Edit</a></li>";

                return $navActionStart . $edit . $delete . $navActionEnd;
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

        return view('pages.jenis-kelompok-masyarakat.index');
    }

    public function create()
    {
        return view('pages.jenis-kelompok-masyarakat.create');
    }

    public function store(JenisKelompokMasyarakatRequest $request): ?\Illuminate\Http\RedirectResponse
    {
        try {
            DB::beginTransaction();
            JenisKelompokMasyarakat::query()->create($request->validated());
            DB::commit();
            toast()->success('Yeeayy !!', 'Data berhasil disimpan');

            return redirect()->route('jenis-kelompok-masyarakat.index');
        } catch (\Throwable $th) {
            DB::rollBack();
            toast()->error('Oppss !!', $th->getMessage());

            return back()->withInput();
        }
    }

    public function edit(JenisKelompokMasyarakat $jenis_kelompok_masyarakat)
    {
        $jenisKelompokMasyarakat = $jenis_kelompok_masyarakat;

        return view('pages.jenis-kelompok-masyarakat.create', compact('jenisKelompokMasyarakat'));
    }

    public function update(JenisKelompokMasyarakatRequest $request, JenisKelompokMasyarakat $jenis_kelompok_masyarakat): ?\Illuminate\Http\RedirectResponse
    {
        try {
            DB::beginTransaction();
            $jenis_kelompok_masyarakat->update($request->validated());
            DB::commit();
            toast()->success('Yeeayy !!', 'Data berhasil disimpan');

            return redirect()->route('jenis-kelompok-masyarakat.index');
        } catch (\Throwable $th) {
            DB::rollBack();
            toast()->error('Oppss !!', $th->getMessage());

            return back()->withInput();
        }
    }

    public function destroy(JenisKelompokMasyarakat $jenis_kelompok_masyarakat): ?\Illuminate\Http\RedirectResponse
    {
        try {
            DB::beginTransaction();
            $jenis_kelompok_masyarakat->delete();
            DB::commit();
            toast()->success('Yeeayy !!', 'Data berhasil dihapus');

            return redirect()->route('jenis-kelompok-masyarakat.index');
        } catch (\Throwable $th) {
            DB::rollBack();
            toast()->error('Oppss !!', $th->getMessage());

            return redirect()->route('jenis-kelompok-masyarakat.index');
        }
    }
}
