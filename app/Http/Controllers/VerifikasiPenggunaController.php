<?php

namespace App\Http\Controllers;

use App\Enums\VerificationStatus;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class VerifikasiPenggunaController extends Controller
{
    private function data()
    {
        $data = User::query()
            ->with('userDetail')
            ->where('is_active', false)
            ->latest();

        return DataTables::of($data)
            ->addColumn('desc_role', fn ($row) => $row->role->getDescription())
            ->addColumn('status_detail', function ($row) {
                return $row->userDetail
                    ? '<span class="badge bg-success">Sudah isi detail</span>'
                    : '<span class="badge bg-warning text-dark">Belum isi detail</span>';
            })
            ->addColumn('action', function ($row) {
                $navActionStart = '<nav class="breadcrumb-container d-inline-block" aria-label="breadcrumb"><ul class="breadcrumb pt-0">';
                $navActionEnd = '</ul></nav>';

                $lihat = "<li class='breadcrumb-item'><a href='" . route('verifikasi-pengguna.show', $row->id) . "' title='Lihat detail' class='fw-bold text-primary'>Lihat detail</a></li>";
                $aktifkan = '';
                if ($row->userDetail) {
                    $aktifkan = "<li class='breadcrumb-item'><a href='" . route('verifikasi-pengguna.aktifkan', $row->id) . "' data-confirm-aktifkan='true' title='Aktifkan pengguna' class='fw-bold text-success'>Aktifkan</a></li>";
                }

                return $navActionStart . $lihat . $aktifkan . $navActionEnd;
            })
            ->rawColumns(['action', 'status_detail'])
            ->toJson();
    }

    public function index()
    {
        if (request()->ajax()) {
            return $this->data();
        }

        return view('pages.verifikasi-pengguna.index');
    }

    public function show(User $user)
    {
        $user->load(['userDetail.desa.kecamatan']);
        return view('pages.verifikasi-pengguna.show', compact('user'));
    }

    public function aktifkan(User $user)
    {
        try {
            DB::beginTransaction();
            $user->update(['is_active' => true]);

            $user->userDetail?->update([
                'verification_status' => VerificationStatus::APPROVED,
                'verified_at' => now(),
                'verified_by' => Auth::id(),
            ]);

            DB::commit();
            toast()->success('Berhasil', 'Pengguna berhasil diaktifkan.');
            return redirect()->route('verifikasi-pengguna.index');
        } catch (\Throwable $th) {
            DB::rollBack();
            toast()->error('Gagal', $th->getMessage());
            return redirect()->route('verifikasi-pengguna.index');
        }
    }
}
