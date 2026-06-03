<?php

namespace App\Http\Controllers;

use App\Enums\RoleUser;
use App\Http\Requests\PenggunaRequest;
use App\Models\Opd;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
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

                $loginAs = '';
                if (auth()->user()->is_super() && ! $data->is_super() && auth()->id() !== $data->id) {
                    $loginAs = "<li class='breadcrumb-item'>
                        <form method='POST' action='".route('pengguna.login-as', $data->id)."' class='d-inline'>
                            ".csrf_field()."
                            <button type='submit' class='btn btn-link p-0 fw-bold text-warning' title='Login sebagai pengguna ini' onclick=\"return confirm('Login sebagai ".e($data->nama ?? $data->email)."?');\">Login As</button>
                        </form>
                    </li>";
                }

                return !$data->is_super() ? $navActionStart.$edit.$loginAs.$delete.$navActionEnd : '-';
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

    public function loginAs(Request $request, User $pengguna): RedirectResponse
    {
        $current = Auth::user();

        // Defensive guards (selain middleware role:super di route)
        abort_unless($current && $current->is_super(), 403);
        abort_if($pengguna->is_super(), 403, 'Tidak boleh impersonate akun super lain.');
        abort_if($current->id === $pengguna->id, 403, 'Tidak perlu impersonate diri sendiri.');
        abort_if(! $pengguna->is_active, 403, 'Akun tidak aktif.');
        // Tolak jika sedang impersonate (cegah berantai)
        abort_if($request->session()->has('impersonator_id'), 403, 'Sedang dalam mode impersonasi. Keluar dulu.');

        $originalId = $current->id;

        Log::info('impersonate.start', [
            'super_user_id' => $originalId,
            'target_user_id' => $pengguna->id,
            'target_username' => $pengguna->username,
            'ip' => $request->ip(),
        ]);

        Auth::loginUsingId($pengguna->id);
        $request->session()->regenerate();
        $request->session()->put('impersonator_id', $originalId);

        toast()->success('Mode Impersonasi', 'Anda login sebagai '.($pengguna->nama ?? $pengguna->email).'.');
        return redirect()->route('home');
    }

    public function stopImpersonating(Request $request): RedirectResponse
    {
        $originalId = $request->session()->pull('impersonator_id');

        if (! $originalId) {
            return redirect()->route('home');
        }

        Log::info('impersonate.stop', [
            'super_user_id' => $originalId,
            'impersonated_user_id' => Auth::id(),
            'ip' => $request->ip(),
        ]);

        Auth::loginUsingId($originalId);
        $request->session()->regenerate();

        toast()->success('Selesai', 'Kembali ke akun super.');
        return redirect()->route('pengguna.index');
    }
}
