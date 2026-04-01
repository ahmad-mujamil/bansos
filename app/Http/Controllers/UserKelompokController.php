<?php

namespace App\Http\Controllers;


use App\Enums\RoleUser;
use App\Http\Requests\UserKelompokRequest;
use App\Models\Organisasi;
use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class UserKelompokController extends Controller
{
    private function data()
    {
        $data =  User::query()
            ->where('opd_id',auth()->user()->opd_id??'-')
            ->whereRole(RoleUser::USER)
            ->latest();

        return DataTables::of($data)
            ->addColumn('desc_role',fn($data)=> $data->role->getDescription())
            ->addColumn('status',fn($data)=> $data->is_active ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-danger">Inactive</span>')
            ->addColumn('action',function($data){
                $navActionStart = '<nav class="breadcrumb-container d-inline-block" aria-label="breadcrumb"><ul class="breadcrumb pt-0">';
                $navActionEnd = "</ul></nav>";

                $delete = "";
                if(auth()->user()->is_opd())
                    $delete = "<li class='breadcrumb-item'><a href='".route('user-kelompok.destroy',$data->id)."' data-confirm-delete='true'
                        title='Hapus Data' class='fw-bold text-danger'>Delete</a></li>";

                $edit = "<li class='breadcrumb-item'><a href='".route('user-kelompok.edit',$data->id)."'  title='Edit Data'
                        class='fw-bold text-success' >Edit</a></li>";

                return !$data->is_opd() ? $navActionStart.$edit.$delete.$navActionEnd : '-';
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

        return view('pages.user-kelompok.index');
    }

    public function create()
    {
        $organisasi = Organisasi::query()
            ->where("opd_id",auth()->user()->opd_id??'-')
            ->get();
        return view('pages.user-kelompok.create',compact('organisasi'));
    }
    public function store(UserKelompokRequest $request)
    {
        try {
            $validated = $request->validated();
            DB::beginTransaction();
            $user = User::query()->create(array_merge(
                Arr::only($validated,["nama","email","username","opd_id","role","password","is_active"]),
                ['jenis_user' => 'KLP']
            ));
            $user->userDetail()->create(Arr::except($validated,["nama","email","username","opd_id","role","password","is_active"]));
            DB::commit();
            toast()->success('Yeeayy !!','Data berhasil disimpan');
            return redirect()->route('user-kelompok.index');
        } catch (\Throwable $th) {
            dd($th);
            toast()->error('Oppss !!',$th->getMessage());
            return back()->withInput()->withErrors($th->getMessage());
        }
    }

    public function edit(User $userKelompok)
    {
        $organisasi = Organisasi::query()->where("opd_id",auth()->user()->opd_id??'-')->get();
        return view('pages.user-kelompok.create', compact('userKelompok', 'organisasi'));
    }
    public function update(UserKelompokRequest $request, User $userKelompok)
    {
        try {
            DB::beginTransaction();
            $validated = $request->validated();
            $userKelompok->update(Arr::only($validated,["nama","email","username","opd_id","role","password","is_active"]));
            $userKelompok->userDetail()->update(Arr::except($validated,["nama","email","username","opd_id","role","password","is_active"]));
            DB::commit();
            toast()->success('Yeeayy !!','Data berhasil disimpan');
            return redirect()->route('user-kelompok.index');
        } catch (\Throwable $th) {
            toast()->error('Oppss !!',$th->getMessage());
            return back()->withInput();
        }
    }
    public function destroy(User $userKelompok)
    {
        try {
            DB::beginTransaction();
            $userKelompok->delete();
            DB::commit();
            toast()->success('Yeeayy !!','Data berhasil dihapus');
            return redirect()->route('user-kelompok.index');
        } catch (\Throwable $th) {
            toast()->error('Oppss !!',$th->getMessage());
            return redirect()->route('user-kelompok.index');
        }
    }
}
