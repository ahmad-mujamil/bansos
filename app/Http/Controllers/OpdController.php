<?php

namespace App\Http\Controllers;

use App\Http\Requests\OpdRequest;
use App\Models\Opd;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class OpdController extends Controller
{
    private function data(): \Illuminate\Http\JsonResponse
    {
        $data = Opd::query()
            ->latest();

        return DataTables::of($data)
            ->addColumn('action', function ($data) {
                $navActionStart = '<nav class="breadcrumb-container d-inline-block" aria-label="breadcrumb"><ul class="breadcrumb pt-0">';
                $navActionEnd = "</ul></nav>";

                $delete = "<li class='breadcrumb-item'><a href='" . route('opd.destroy', $data->id) . "' data-confirm-delete='true'
                        title='Hapus Data' class='fw-bold text-danger'>Delete</a></li>";

                $edit = "<li class='breadcrumb-item'><a href='" . route('opd.edit', $data->id) . "'  title='Edit Data'
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

        return view('pages.opd.index');
    }

    public function create()
    {
        return view('pages.opd.create');
    }

    public function store(OpdRequest $request): ?\Illuminate\Http\RedirectResponse
    {
        try {
            DB::beginTransaction();
            Opd::query()->create($request->validated());
            DB::commit();
            toast()->success('Yeeayy !!', 'Data berhasil disimpan');
            return redirect()->route('opd.index');
        } catch (\Throwable $th) {
            toast()->error('Oppss !!', $th->getMessage());
            return back()->withInput();
        }
    }

    public function edit(Opd $opd)
    {
        return view('pages.opd.create', compact('opd'));
    }

    public function update(OpdRequest $request, Opd $opd): ?\Illuminate\Http\RedirectResponse
    {
        try {
            DB::beginTransaction();
            $opd->update($request->validated());
            DB::commit();
            toast()->success('Yeeayy !!', 'Data berhasil disimpan');
            return redirect()->route('opd.index');
        } catch (\Throwable $th) {
            toast()->error('Oppss !!', $th->getMessage());
            return back()->withInput();
        }
    }

    public function destroy(Opd $opd): ?\Illuminate\Http\RedirectResponse
    {
        try {
            DB::beginTransaction();
            $opd->delete();
            DB::commit();
            toast()->success('Yeeayy !!', 'Data berhasil dihapus');
            return redirect()->route('opd.index');
        } catch (\Throwable $th) {
            toast()->error('Oppss !!', $th->getMessage());
            return redirect()->route('opd.index');
        }
    }
}

