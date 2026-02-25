<?php

namespace App\Http\Controllers;

use App\Enums\JenisUser;
use App\Enums\VerificationStatus;
use App\Models\Kecamatan;
use App\Models\UserDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class UserDetailController extends Controller
{
    public function create()
    {
        $user = auth()->user();
        $userDetail = $user->userDetail;
        if ($userDetail) {
            $userDetail->load('desa.kecamatan.desa', 'verifiedBy');
        }
        $isLocked = $userDetail && (
            $userDetail->verification_status === VerificationStatus::APPROVED
            || $user->is_active
        );
        $kecamatans = Kecamatan::query()->with('desa')->orderBy('nama')->get();
        $kecamatansData = $kecamatans->map(function ($k) {
            return [
                'id' => $k->id,
                'nama' => $k->nama,
                'desa' => $k->desa->map(fn ($d) => ['id' => $d->id, 'nama' => $d->nama])->values()->all(),
            ];
        })->values()->all();

        return view('pages.user-detail.form', [
            'userDetail' => $userDetail,
            'kecamatans' => $kecamatans,
            'kecamatansData' => $kecamatansData,
            'jenisUserOptions' => JenisUser::cases(),
            'isLocked' => $isLocked,
        ]);
    }

    public function store(Request $request)
    {
        $user = auth()->user();

        if ($user->userDetail) {
            return redirect()->route('user-detail.create')
                ->with('info', 'Data detail sudah ada. Gunakan form ini untuk memperbarui.');
        }

        $validated = $this->validateRequest($request);

        try {
            DB::beginTransaction();
            $validated['user_id'] = $user->id;
            $validated['type'] = $validated['type'] ?? JenisUser::INDIVIDUAL->value;

            if ($request->hasFile('file_ktp')) {
                $validated['file_ktp'] = $request->file('file_ktp')->store('user-detail/ktp', 'public');
            }
            if ($request->hasFile('file_surat_kuasa')) {
                $validated['file_surat_kuasa'] = $request->file('file_surat_kuasa')->store('user-detail/surat-kuasa', 'public');
            }

            UserDetail::query()->create($validated);
            DB::commit();
            toast()->success('Berhasil', 'Data detail berhasil disimpan. Menunggu verifikasi admin.');
            return redirect()->route('home');
        } catch (\Throwable $e) {
            DB::rollBack();
            toast()->error('Gagal', $e->getMessage());
            return back()->withInput();
        }
    }

    public function update(Request $request)
    {
        $user = auth()->user();
        $userDetail = $user->userDetail;

        if (!$userDetail) {
            return redirect()->route('user-detail.create');
        }

        if ($userDetail->verification_status === VerificationStatus::APPROVED || $user->is_active) {
            toast()->warning('Data tidak dapat diubah', 'Data detail sudah diverifikasi/aktif dan tidak dapat diubah.');
            return redirect()->route('user-detail.create');
        }

        $validated = $this->validateRequest($request, $userDetail);

        try {
            DB::beginTransaction();

            if ($request->hasFile('file_ktp')) {
                if ($userDetail->file_ktp) {
                    Storage::disk('public')->delete($userDetail->file_ktp);
                }
                $validated['file_ktp'] = $request->file('file_ktp')->store('user-detail/ktp', 'public');
            }
            if ($request->hasFile('file_surat_kuasa')) {
                if ($userDetail->file_surat_kuasa) {
                    Storage::disk('public')->delete($userDetail->file_surat_kuasa);
                }
                $validated['file_surat_kuasa'] = $request->file('file_surat_kuasa')->store('user-detail/surat-kuasa', 'public');
            }

            $userDetail->update($validated);
            DB::commit();
            toast()->success('Berhasil', 'Data detail berhasil diperbarui.');
            return redirect()->route('home');
        } catch (\Throwable $e) {
            DB::rollBack();
            toast()->error('Gagal', $e->getMessage());
            return back()->withInput();
        }
    }

    private function validateRequest(Request $request, ?UserDetail $userDetail = null): array
    {
        $type = $request->input('type', JenisUser::INDIVIDUAL->value);
        $isIndividual = $type === JenisUser::INDIVIDUAL->value;

        $rules = [
            'type' => ['required', Rule::enum(JenisUser::class)],
            'nama_user' => 'required|string|max:255',
            'alamat' => 'nullable|string',
            'desa_id' => 'nullable|exists:desa,id',
            'phone' => 'nullable|string|max:20',
        ];

        if ($isIndividual) {
            $rules['nama_personal'] = 'required|string|max:255';
            $rules['nik'] = 'required|string|size:16';
            $rules['file_ktp'] = $userDetail ? 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048' : 'required|file|mimes:jpeg,png,jpg,pdf|max:2048';
        } else {
            $rules['nama_lembaga'] = 'required|string|max:255';
            $rules['file_surat_kuasa'] = $userDetail ? 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048' : 'required|file|mimes:jpeg,png,jpg,pdf|max:2048';
        }

        return $request->validate($rules);
    }
}
