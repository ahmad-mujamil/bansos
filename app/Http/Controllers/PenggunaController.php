<?php

namespace App\Http\Controllers;

use App\Enums\RoleUser;
use App\Http\Requests\PenggunaRequest;
use App\Models\Opd;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class PenggunaController extends Controller
{
    private function data()
    {
        $data =  User::query()
            ->latest();

        return DataTables::of($data)
            ->addColumn('desc_role',fn($data)=> $data->role->getDescription())
            ->addColumn('status',fn($data)=> $data->is_active ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-danger">Inactive</span>')
            ->addColumn('action',function($data){
                $navActionStart = '<nav class="breadcrumb-container d-inline-block" aria-label="breadcrumb"><ul class="breadcrumb pt-0">';
                $navActionEnd = "</ul></nav>";

                $delete = "";
                if(auth()->user()->is_super())
                    $delete = "<li class='breadcrumb-item'><a href='".route('pengguna.destroy',$data->id)."' data-confirm-delete='true'
                        title='Hapus Data' class='fw-bold text-danger'>Delete</a></li>";

                $edit = "<li class='breadcrumb-item'><a href='".route('pengguna.edit',$data->id)."'  title='Edit Data'
                        class='fw-bold text-success' >Edit</a></li>";

                return !$data->is_super() ? $navActionStart.$edit.$delete.$navActionEnd : '-';
            })
            ->rawColumns(['action','status'])
            ->toJson();
    }
    public function index()
    {
        confirmDelete("Delete Data", "Are you sure you want to delete?");
        if(request()->ajax()){
            return $this->data();
        }

        return view('pages.pengguna.index');
    }

    public function create()
    {
        $members = User::query()
            ->where('role',RoleUser::USER)
            ->where('is_active',true)
            ->get();
        $opds = Opd::query()->orderBy('nama')->get();
        return view('pages.pengguna.create',compact('members', 'opds'));
    }
    public function store(PenggunaRequest $request)
    {
        try {
            DB::beginTransaction();
            User::query()->create($request->validated());
            DB::commit();
            toast()->success('Yeeayy !!','Data berhasil disimpan');
            return redirect()->route('pengguna.index');
        } catch (\Throwable $th) {
            toast()->error('Oppss !!',$th->getMessage());
            return back()->withInput();
        }
    }

    public function edit(User $pengguna)
    {

        // $members = User::query()
        //     ->where('role',RoleUser::USER)
        //     ->where('is_active',true)
        //     ->get();
        $opds = Opd::query()->orderBy('nama')->get();
        return view('pages.pengguna.create', compact('pengguna', 'opds'));
    }
    public function update(PenggunaRequest $request, User $pengguna)
    {
        try {
            DB::beginTransaction();
            $validated = $request->validated();

            if ($pengguna->email == $validated['email']) {
                unset($validated['email']);
            }
            if ($pengguna->username == $validated['username']) {
                unset($validated['username']);
            }
            if (empty($validated['password'])) {
                unset($validated['password']);
            } else {
                $validated['password'] = bcrypt($validated['password']);
            }

            $pengguna->update($validated);
            DB::commit();
            toast()->success('Yeeayy !!','Data berhasil disimpan');
            return redirect()->route('pengguna.index');
        } catch (\Throwable $th) {
            toast()->error('Oppss !!',$th->getMessage());
            return back()->withInput();
        }
    }
    public function destroy(User $pengguna)
    {
        try {
            DB::beginTransaction();
            $pengguna->delete();
            DB::commit();
            toast()->success('Yeeayy !!','Data berhasil dihapus');
            return redirect()->route('pengguna.index');
        } catch (\Throwable $th) {
            toast()->error('Oppss !!',$th->getMessage());
            return redirect()->route('pengguna.index');
        }
    }
}
