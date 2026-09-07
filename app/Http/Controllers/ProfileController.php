<?php

namespace App\Http\Controllers;

use App\Enums\JenjangSekolah;
use App\Http\Requests\PenggunaRequest;
use App\Http\Requests\ProfileRequest;
use App\Http\Requests\UpdatePasswordRequest;
use App\Models\Pengguna;
use App\Models\Sekolah;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class ProfileController extends Controller
{
    public function index()
    {
        return view('profile');
    }

    public function updateProfile(ProfileRequest $request)
    {
        try {
            DB::beginTransaction();

            auth()->user()->update(Arr::except($request->validated(), ['foto']));
            if ($request->hasFile('foto')) {
                // Delete old photo if exists
                if (auth()->user()->foto) {
                    Storage::disk('public')->delete(auth()->user()->foto);
                }

                auth()->user()->update(['foto' => $request->file('foto')->store('profile', 'public')]);
            }

            DB::commit();
            toast()->success('Yeeayy !!','Data berhasil disimpan');
            return redirect()->back();
        } catch (\Throwable $e) {
            return redirect()->back()->withErrors(['error' => 'Failed to update profile: ' . $e->getMessage()]);
        }

    }

    public function security()
    {
        return view('security');
    }

    public function updatePassword(UpdatePasswordRequest $request)
    {
        try {
            DB::beginTransaction();

            auth()->user()->update([
                'password' => bcrypt($request->password)
            ]);

            DB::commit();
            toast()->success('Berhasil', 'Password berhasil diubah.');
            return redirect()->back();
        } catch (\Throwable $e) {
            DB::rollBack();
            $message = app()->isLocal() ? $e->getMessage() : 'Password gagal diubah.';
            toast()->error('Gagal', $message);
            return redirect()->back()->withErrors(['error' => $message]);
        }

    }
}
