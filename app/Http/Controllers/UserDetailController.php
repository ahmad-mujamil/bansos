<?php

namespace App\Http\Controllers;

use App\Enums\JenisUser;
use App\Enums\VerificationStatus;
use App\Enums\JenisOrganisasi;
use App\Enums\JabatanOrganisasi;
use App\Enums\JenisDokumen;
use App\Models\Kecamatan;
use App\Models\Penduduk;
use App\Models\UserDetail;
use App\Models\Opd;
use App\Models\Organisasi;
use App\Models\OrganisasiDetail;
use App\Models\OrganisasiDokumen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Unique;

class UserDetailController extends Controller
{
    public function create()
    {
        $user = auth()->user();
        $userDetail = $user->userDetail;
        if ($userDetail) {
            $userDetail->load('desa.kecamatan.desa', 'verifiedBy', 'organisasi.opd', 'organisasi.kecamatan', 'organisasi.desa', 'organisasi.organisasiDetail', 'organisasi.organisasiDokumen');
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

        $opds = Opd::query()
            ->with(['organisasi' => function ($q) {
                $q->where('is_active', true)
                    ->where('jenis', JenisOrganisasi::KELOMPOK)
                    ->with(['kecamatan', 'desa'])
                    ->withCount('organisasiDetail');
            }])
            ->orderBy('nama')
            ->get();

        // List kelompok di select hanya yang aktif (yang belum aktif tidak ditampilkan)
        $opdsData = $opds->map(function ($opd) {
            $organisasiList = $opd->organisasi->map(function ($org) {
                return [
                    'id' => $org->id,
                    'nama' => $org->nama,
                    'nomor' => $org->nomor,
                    'tgl_pembentukan' => optional($org->tgl_pembentukan)->format('d-m-Y'),
                    'kecamatan' => $org->kecamatan->nama ?? null,
                    'desa' => $org->desa->nama ?? null,
                    'is_active' => $org->is_active,
                    'anggota_count' => (int) ($org->organisasi_detail_count ?? 0),
                    'anggota_url' => route('kelompok-masyarakat.anggota.index', $org->id),
                ];
            })->values()->all();

            return [
                'id' => $opd->id,
                'nama' => $opd->nama,
                'organisasi' => $organisasiList,
            ];
        })->values()->all();

        // Jika user punya kelompok yang belum aktif, tampilkan form tambah kelompok baru
        $selectedOrganisasiInactive = $userDetail && $userDetail->organisasi_id && optional($userDetail->organisasi)->is_active === false;

        $preselectOpdId = old('opd_id', $userDetail?->organisasi?->opd_id ?? null);
        $penduduks = Penduduk::query()->orderBy('nama')->get();

        return view('pages.user-detail.form', [
            'userDetail' => $userDetail,
            'kecamatans' => $kecamatans,
            'kecamatansData' => $kecamatansData,
            'jenisUserOptions' => JenisUser::cases(),
            'isLocked' => $isLocked,
            'opds' => $opds,
            'opdsData' => $opdsData,
            'preselectOpdId' => $preselectOpdId,
            'penduduks' => $penduduks,
            'selectedOrganisasiInactive' => $selectedOrganisasiInactive,
        ]);
    }

    public function store(Request $request)
    {

        // return $request;
        $user = auth()->user();

        if ($user->userDetail) {
            return redirect()->route('user-detail.create')
                ->with('info', 'Data detail sudah ada. Gunakan form ini untuk memperbarui.');
        }

        $type = $request->input('type', JenisUser::INDIVIDUAL->value);
        $isLembaga = $type != JenisUser::INDIVIDUAL->value ;
        $organisasiId = $request->input('organisasi_id');

        // Tambah kelompok baru: simpan data kelompok dulu, pakai id-nya sebagai organisasi_id di user_detail
        // Periksa langsung nilai organisasi_id tanpa in_array agar kasus "__new__" terdeteksi dengan benar
        $adaDataKelompokBaru = $request->filled('nama_kelompok')
            || $request->filled('nomor_kelompok')
            || $request->filled('tgl_pembentukan_kelompok')
            || $request->filled('kecamatan_id_kelompok')
            || $request->filled('desa_id_kelompok');

        $tambahKelompokBaru = $isLembaga && (
            $request->filled('tambah_kelompok_baru')
            || $organisasiId === '__new__'
            || $organisasiId === ''
            || $organisasiId === null
            || $adaDataKelompokBaru
        );

        // return $tambahKelompokBaru;
        if ($tambahKelompokBaru) {
            return $this->storeWithKelompokBaru($request, $user);
        }

        $validated = $this->validateRequest($request);

        // Fallback: lembaga dengan data kelompok terisi atau organisasi_id bukan id asli → simpan lewat alur kelompok baru
        $adaDataKelompokFallback = $request->filled('nama_kelompok') || $request->filled('nomor_kelompok') || $request->filled('tgl_pembentukan_kelompok');
        if ($isLembaga && ($adaDataKelompokFallback || in_array($validated['organisasi_id'] ?? null, ['__new__', '', null], true))) {
            return $this->storeWithKelompokBaru($request, $user);
        }

        try {
            DB::beginTransaction();
            $this->createUserDetailOnly($request, $user, $validated, []);
            DB::commit();
            toast()->success('Berhasil', 'Data detail berhasil disimpan. Menunggu verifikasi admin.');
            return redirect()->route('home');
        } catch (\Throwable $e) {
            DB::rollBack();
            toast()->error('Gagal', $e->getMessage());
            return back()->withInput();
        }
    }

    /**
     * Hapus user_detail dan kelompok (organisasi user yang belum aktif) agar bisa simpan ulang.
     * Mengembalikan path file lama (file_ktp, file_surat_kuasa) untuk dipakai jika tidak ada upload baru.
     */
    private function deleteUserDetailAndKelompok(UserDetail $userDetail): array
    {
        $organisasi = $userDetail->organisasi;
        if ($organisasi && $organisasi->is_active === false && (string) $organisasi->user_id === (string) auth()->id()) {
            foreach ($organisasi->organisasiDokumen as $d) {
                $d->clearMediaCollection('dokumen');
                $d->delete();
            }
            $organisasi->organisasiDetail()->delete();
            $organisasi->delete();
        }

        $oldFiles = [
            'file_ktp' => $userDetail->file_ktp,
            'file_surat_kuasa' => $userDetail->file_surat_kuasa,
        ];
        $userDetail->delete();
        return $oldFiles;
    }

    /**
     * Simpan user detail dengan kelompok baru + anggota + dokumen dalam satu form.
     * Alur: buat Organisasi dulu → anggota → dokumen → UserDetail (organisasi_id = kelompok baru).
     */
    private function storeWithKelompokBaru(Request $request, $user)
    {
        $rules = $this->rulesForKelompokBaru();
        $anggotas = $request->input('anggota', []);
        foreach ($anggotas as $i => $_) {
            $rules["anggota.{$i}.penduduk_id"] = ['nullable', 'exists:penduduk,id'];
            $rules["anggota.{$i}.jabatan"] = ['nullable', Rule::enum(JabatanOrganisasi::class)];
        }
        $dokumens = $request->input('dokumen', []);
        foreach ($dokumens as $i => $_) {
            $rules["dokumen.{$i}.jenis_dokumen"] = ['nullable', Rule::enum(JenisDokumen::class)];
            $rules["dokumen.{$i}.keterangan"] = ['nullable', 'string', 'max:255'];
            $rules["dokumen.{$i}.file"] = ['nullable', 'file', 'mimes:pdf,jpeg,jpg,png,webp', 'max:10240'];
        }
        $validated = $request->validate($rules);

        try {
            DB::beginTransaction();
            $this->doCreateWithKelompokBaru($request, $user, $validated);
            DB::commit();
            toast()->success('Berhasil', 'Data kelompok dan data diri berhasil disimpan. Menunggu verifikasi admin.');
            return redirect()->route('home');
        } catch (\Throwable $e) {
            DB::rollBack();
            toast()->error('Gagal', $e->getMessage());
            return back()->withInput();
        }
    }

    private function rulesForKelompokBaru(?string $ignoreOrganisasiId = null, bool $fileSuratOptional = false): array
    {
        $nomorRule = ['required', 'string', 'max:100', Rule::unique('organisasi', 'nomor')];
        if ($ignoreOrganisasiId) {
            $nomorRule[count($nomorRule) - 1] = Rule::unique('organisasi', 'nomor')->ignore($ignoreOrganisasiId);
        }
        return [
            'type' => ['required', Rule::enum(JenisUser::class)],
            'nama_user' => 'required|string|max:255',
            'alamat' => 'nullable|string',
            'desa_id' => 'nullable|exists:desa,id',
            'phone' => 'nullable|string|max:20',
            'opd_id' => 'required|exists:opd,id',
            'nama_kelompok' => 'required|string|max:255',
            'nomor_kelompok' => $nomorRule,
            'tgl_pembentukan_kelompok' => 'required|date',
            'kecamatan_id_kelompok' => 'required|exists:kecamatan,id',
            'desa_id_kelompok' => 'required|exists:desa,id',
            'file_surat_kuasa' => $fileSuratOptional ? 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048' : 'required|file|mimes:jpeg,png,jpg,pdf|max:2048',
        ];
    }

    private function doCreateWithKelompokBaru(Request $request, $user, array $validated, array $oldFiles = []): void
    {
        $organisasi = Organisasi::query()->create([
            'nama' => $validated['nama_kelompok'],
            'nomor' => $validated['nomor_kelompok'],
            'tgl_pembentukan' => $validated['tgl_pembentukan_kelompok'],
            'jenis' => JenisOrganisasi::KELOMPOK,
            'kecamatan_id' => $validated['kecamatan_id_kelompok'],
            'desa_id' => $validated['desa_id_kelompok'],
            'opd_id' => $validated['opd_id'],
            'user_id' => $user->id,
            'is_active' => false,
        ]);

        $anggotas = $request->input('anggota', []);
        foreach ($anggotas as $a) {
            if (!empty($a['penduduk_id']) && !empty($a['jabatan'])) {
                OrganisasiDetail::query()->create([
                    'organisasi_id' => $organisasi->id,
                    'penduduk_id' => $a['penduduk_id'],
                    'jabatan' => $a['jabatan'],
                ]);
            }
        }

        $dokumens = $request->input('dokumen', []);
        foreach ($dokumens as $i => $d) {
            if (!empty($d['jenis_dokumen']) && !empty($d['keterangan']) && $request->hasFile("dokumen.{$i}.file")) {
                $dokumen = OrganisasiDokumen::query()->create([
                    'organisasi_id' => $organisasi->id,
                    'jenis_dokumen' => $d['jenis_dokumen'],
                    'keterangan' => $d['keterangan'],
                ]);
                $dokumen->addMediaFromRequest("dokumen.{$i}.file")->toMediaCollection('dokumen');
            }
        }

        $userDetailData = [
            'user_id' => $user->id,
            'type' => $validated['type'],
            'nama_user' => $validated['nama_user'],
            'alamat' => $validated['alamat'] ?? null,
            'desa_id' => $validated['desa_id'] ?? null,
            'phone' => $validated['phone'] ?? null,
            'organisasi_id' => $organisasi->id,
            'nama_lembaga' => $organisasi->nama,
        ];
        if ($request->hasFile('file_surat_kuasa')) {
            $userDetailData['file_surat_kuasa'] = $request->file('file_surat_kuasa')->store('user-detail/surat-kuasa', 'public');
        } elseif (!empty($oldFiles['file_surat_kuasa'])) {
            $userDetailData['file_surat_kuasa'] = $oldFiles['file_surat_kuasa'];
        }
        UserDetail::query()->create($userDetailData);
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

        $type = $request->input('type', JenisUser::INDIVIDUAL->value);
        $isLembaga = $type !== JenisUser::INDIVIDUAL->value;
        $organisasiId = $request->input('organisasi_id');
        $adaDataKelompokBaru = $request->filled('nama_kelompok')
            || $request->filled('nomor_kelompok')
            || $request->filled('tgl_pembentukan_kelompok')
            || $request->filled('kecamatan_id_kelompok')
            || $request->filled('desa_id_kelompok');
        $tambahKelompokBaru = $isLembaga && (
            $request->filled('tambah_kelompok_baru')
            || $organisasiId === '__new__'
            || $organisasiId === ''
            || $organisasiId === null
            || $adaDataKelompokBaru
        );

        try {
            DB::beginTransaction();

            if ($tambahKelompokBaru) {
                $ignoreOrgId = ($userDetail->organisasi && $userDetail->organisasi->is_active === false)
                    ? $userDetail->organisasi_id
                    : null;
                $rules = $this->rulesForKelompokBaru($ignoreOrgId, true);
                $anggotas = $request->input('anggota', []);
                foreach ($anggotas as $i => $_) {
                    $rules["anggota.{$i}.penduduk_id"] = ['nullable', 'exists:penduduk,id'];
                    $rules["anggota.{$i}.jabatan"] = ['nullable', Rule::enum(JabatanOrganisasi::class)];
                }
                $dokumens = $request->input('dokumen', []);
                foreach ($dokumens as $i => $_) {
                    $rules["dokumen.{$i}.jenis_dokumen"] = ['nullable', Rule::enum(JenisDokumen::class)];
                    $rules["dokumen.{$i}.keterangan"] = ['nullable', 'string', 'max:255'];
                    $rules["dokumen.{$i}.file"] = ['nullable', 'file', 'mimes:pdf,jpeg,jpg,png,webp', 'max:10240'];
                }
                $validated = $request->validate($rules);
                $oldFiles = [
                    'file_surat_kuasa' => $userDetail->file_surat_kuasa,
                ];
                $this->deleteUserDetailAndKelompok($userDetail);
                $user->unsetRelation('userDetail');
                $this->doCreateWithKelompokBaru($request, $user, $validated, $oldFiles);
                DB::commit();
                toast()->success('Berhasil', 'Data detail dan kelompok berhasil diperbarui.');
                return redirect()->route('home');
            }

            $validated = $this->validateRequest($request, $userDetail);
            $oldFiles = $this->deleteUserDetailAndKelompok($userDetail);
            $user->unsetRelation('userDetail');
            $this->createUserDetailOnly($request, $user, $validated, $oldFiles);
            DB::commit();
            toast()->success('Berhasil', 'Data detail berhasil diperbarui.');
            return redirect()->route('home');
        } catch (\Throwable $e) {
            DB::rollBack();
            toast()->error('Gagal', $e->getMessage());
            return back()->withInput();
        }
    }

    /**
     * Buat satu record UserDetail (tanpa kelompok baru). Pakai oldFiles jika tidak ada upload baru.
     */
    private function createUserDetailOnly(Request $request, $user, array $validated, array $oldFiles = []): void
    {
        $validated['user_id'] = $user->id;
        $validated['type'] = $validated['type'] ?? JenisUser::INDIVIDUAL->value;

        if (isset($validated['organisasi_id']) && in_array($validated['organisasi_id'], ['__new__', '', null], true)) {
            unset($validated['organisasi_id']);
        }
        if (isset($validated['organisasi_id'])) {
            $organisasi = Organisasi::query()->find($validated['organisasi_id']);
            if ($organisasi) {
                $validated['nama_lembaga'] = $organisasi->nama;
            }
        }

        if ($request->hasFile('file_ktp')) {
            $validated['file_ktp'] = $request->file('file_ktp')->store('user-detail/ktp', 'public');
        } elseif (!empty($oldFiles['file_ktp'])) {
            $validated['file_ktp'] = $oldFiles['file_ktp'];
        }
        if ($request->hasFile('file_surat_kuasa')) {
            $validated['file_surat_kuasa'] = $request->file('file_surat_kuasa')->store('user-detail/surat-kuasa', 'public');
        } elseif (!empty($oldFiles['file_surat_kuasa'])) {
            $validated['file_surat_kuasa'] = $oldFiles['file_surat_kuasa'];
        }

        UserDetail::query()->create($validated);
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
            'organisasi_id' => 'nullable|exists:organisasi,id',
            'phone' => 'nullable|string|max:20',
        ];

        if ($isIndividual) {
            $rules['nama_personal'] = 'required|string|max:255';
            $rules['nik'] = 'required|string|size:16';
            $rules['file_ktp'] = $userDetail ? 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048' : 'required|file|mimes:jpeg,png,jpg,pdf|max:2048';
        } else {
            // Lembaga: organisasi_id wajib & harus ada di DB, KECUALI saat tambah kelompok baru (organisasi_id __new__/kosong atau nama_kelompok terisi)
            $orgId = $request->input('organisasi_id');
            $isTambahKelompokBaru = $request->filled('nama_kelompok')
                || in_array($orgId, ['__new__', '', null], true);
            if ($isTambahKelompokBaru) {
                $rules['organisasi_id'] = 'nullable';
            } else {
                $rules['organisasi_id'] = 'required|exists:organisasi,id';
            }
            $rules['nama_lembaga'] = 'nullable|string|max:255';
            $rules['file_surat_kuasa'] = $userDetail ? 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048' : 'required|file|mimes:jpeg,png,jpg,pdf|max:2048';
        }

        return $request->validate($rules);
    }

    /**
     * Simpan kelompok baru dari form user-detail (is_active = false).
     * Redirect ke halaman lengkapi anggota & dokumen.
     */
    public function storeKelompok(Request $request)
    {
        $user = auth()->user();
        $validated = $request->validate([
            'opd_id' => 'required|exists:opd,id',
            'nama' => 'required|string|max:255',
            'nomor' => ['required', 'string', 'max:100', Rule::unique('organisasi', 'nomor')],
            'tgl_pembentukan' => 'required|date',
            'kecamatan_id' => 'required|exists:kecamatan,id',
            'desa_id' => 'required|exists:desa,id',
        ]);

        try {
            DB::beginTransaction();
            $organisasi = Organisasi::query()->create([
                'nama' => $validated['nama'],
                'nomor' => $validated['nomor'],
                'tgl_pembentukan' => $validated['tgl_pembentukan'],
                'jenis' => JenisOrganisasi::KELOMPOK,
                'kecamatan_id' => $validated['kecamatan_id'],
                'desa_id' => $validated['desa_id'],
                'opd_id' => $validated['opd_id'],
                'user_id' => $user->id,
                'is_active' => false,
            ]);
            DB::commit();
            toast()->success('Berhasil', 'Data kelompok berhasil ditambahkan. Lengkapi anggota dan dokumen di bawah.');
            return redirect()->route('user-detail.create', ['organisasi_id' => $organisasi->id]);
        } catch (\Throwable $e) {
            DB::rollBack();
            toast()->error('Gagal', $e->getMessage());
            return redirect()->route('user-detail.create')->withInput();
        }
    }
}
