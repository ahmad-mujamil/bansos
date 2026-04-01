<?php

namespace App\Http\Controllers\Auth;

use App\Enums\RoleUser;
use App\Enums\StatusUser;
use App\Enums\JenisUser;
use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Models\Organisasi;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class RegisterController extends Controller
{

    protected $redirectTo = '/login';

    public function __construct()
    {
        $this->middleware('guest');
    }

    public function showRegistrationForm()
    {
        $organisasiAktif = Organisasi::query()
            ->selectRaw('jenis, COUNT(*) as total')
            ->where('is_active', true)
            ->groupBy('jenis')
            ->get()->pluck('total', 'jenis')->toArray();

        return view('auth.register', [
            'title' => 'Registrasi',
            'description' => 'Daftar akun baru',
            'organisasiAktif' => $organisasiAktif]);
    }

    public function register(RegisterRequest $request)
    {
        try {
            DB::beginTransaction();

            $validated = $request->validated();

            User::create([
                'nama' => $validated['nama'],
                'email' => $validated['email'],
                'username' => $validated['username'],
                'password' => $validated['password'],
                'role' => RoleUser::USER,
                'jenis_user' => $validated['jenis_user'],
                'is_active' => false,
            ]);

            DB::commit();

            toast()->success('Berhasil !!', 'Registrasi berhasil! Silakan login dengan akun Anda.');
            return redirect()->route('login');
        } catch (\Throwable $th) {
            toast()->error('Oppss !!', 'Terjadi kesalahan saat registrasi. Silakan coba lagi.');
            return back()->withInput();
        }
    }
}
