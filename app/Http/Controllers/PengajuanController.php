<?php

namespace App\Http\Controllers;

use App\Enums\JenisOrganisasi;
use App\Enums\JenisPengajuan;
use App\Enums\JenisUser;
use App\Enums\MomenSnapshot;
use App\Enums\PengajuanStatus;
use App\Models\Desa;
use App\Models\JenisBantuan;
use App\Models\Kecamatan;
use App\Models\Organisasi;
use App\Models\OrganisasiDetail;
use App\Models\Penduduk;
use App\Models\Pengajuan;
use App\Models\PengajuanLog;
use App\Services\PengajuanSnapshotService;
use App\Services\RosterKelompokService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class PengajuanController extends Controller
{
    public function index()
    {
        $pengajuan = auth()->user()
            ->pengajuan()
            ->latest()
            ->get();

        return view('pages.pengajuan.index', compact('pengajuan'));
    }

    public function create()
    {
        $jenisUser = auth()->user()->jenis_user?->value ?? null;
        $jenisOptions = match ($jenisUser) {
            'IND' => [JenisPengajuan::BANSOS],
            'KLP' => [JenisPengajuan::BANTUAN_KELOMPOK],
            default => [JenisPengajuan::HIBAH],
        };

        $kelompokList = Organisasi::query()
            ->where('jenis', JenisOrganisasi::KELOMPOK)
            ->where('is_active', true)
            // Kelompok hanya muncul bila dibuat pada/atau sebelum tahun terpilih.
            ->where('tahun_anggaran', '<=', tahun_aktif())
            ->orderBy('nama')
            ->get(['id', 'nama']);
        $pendudukList = Penduduk::query()
            ->orderBy('nama')
            ->get(['id', 'nama', 'nik']);

        $jenis = $this->jenisPengajuanForUser();

        $jenisBantuanKelompokList = JenisBantuan::query()
            ->where('kategori', 'bantuan_kelompok')
            ->orderBy('nama')
            ->get(['id', 'nama', 'keterangan']);

        $kecamatans = Kecamatan::query()->with('desa')->orderBy('nama')->get();

        $organisasiId = auth()->user()->userDetail?->organisasi_id;
        $this->seedRosterKelompok($organisasiId);
        $selectedPendudukId = old('penduduk_id');
        $pendudukIsValidMap = $this->pendudukIsValidMap();
        $simpanDiblokir = $this->shouldBlockSimpanPengajuan($jenis, $selectedPendudukId, $organisasiId, $pendudukIsValidMap);
        $kelompokTanpaAnggota = $jenis !== JenisPengajuan::BANSOS->value
            && $organisasiId
            && $this->jumlahAnggotaKelompok($organisasiId) < 1;
        $kelompokSimpanDiblokir = $kelompokTanpaAnggota || ($jenis !== JenisPengajuan::BANSOS->value
            && $organisasiId
            && $this->kelompokMemilikiAnggotaBelumTerverifikasi($organisasiId));

        // Satu kelompok hanya boleh mengajukan sekali per tahun anggaran.
        $kelompokSudahMengajukan = $jenis !== JenisPengajuan::BANSOS->value
            && $organisasiId
            && Pengajuan::pengajuanKelompokTahun($organisasiId) !== null;
        $simpanDiblokir = $simpanDiblokir || $kelompokSudahMengajukan;

        $anggotaBelumTerverifikasi = $organisasiId
            ? (Organisasi::query()->find($organisasiId)?->anggotaBelumTerverifikasiData() ?? collect())
            : collect();

        return view('pages.pengajuan.form', [
            'pengajuan' => null,
            'kelompokList' => $kelompokList,
            'pendudukList' => $pendudukList,
            'jenisOptions' => $jenisOptions,
            'jenisBantuanKelompokList' => $jenisBantuanKelompokList,
            'jenis' => $jenis,
            'kecamatans' => $kecamatans,
            'selectedPendudukId' => $selectedPendudukId,
            'pendudukIsValidMap' => $pendudukIsValidMap,
            'simpanDiblokir' => $simpanDiblokir,
            'kelompokSimpanDiblokir' => $kelompokSimpanDiblokir,
            'kelompokTanpaAnggota' => $kelompokTanpaAnggota,
            'kelompokSudahMengajukan' => $kelompokSudahMengajukan,
            'anggotaBelumTerverifikasi' => $anggotaBelumTerverifikasi,
        ]);
    }

    public function store(Request $request)
    {

        $validated = $this->validatePengajuan($request);
        $this->validatePengajuanVerifikasi($request);
        // return $request->all();
        try {
            DB::beginTransaction();
            $pengajuan = new Pengajuan;
            $pengajuan->user_id = auth()->id();
            $pengajuan->kode_pengajuan = $this->generateKodePengajuan();
            $pengajuan->jenis_bantuan_id = $validated['jenis_bantuan_id'] ?? null;
            $pengajuan->kategori_pengajuan = JenisPengajuan::from($validated['jenis']);
            $pengajuan->judul = $validated['judul'];
            $pengajuan->lokasi = $validated['lokasi'] ?? null;
            $pengajuan->nilai = $validated['nilai'];
            $pengajuan->opd_id = $validated['opd_id'];
            $pengajuan->organisasi_id = $validated['organisasi_id'];
            $pengajuan->desa_id = $validated['desa_id'] ?? null;
            $pengajuan->status = PengajuanStatus::DRAFT;
            $pengajuan->save();

            if ($request->hasFile('file_pengajuan')) {
                $pengajuan->addMediaFromRequest('file_pengajuan')->toMediaCollection('pengajuan');
            }

            $this->logPengajuan($pengajuan, 'created', null, PengajuanStatus::DRAFT->value);
            DB::commit();
            toast()->success('Berhasil', 'Pengajuan berhasil disimpan.');

            return redirect()->route('pengajuan.index');
        } catch (\Throwable $e) {
            return $e;
            DB::rollBack();
            toast()->error('Gagal', $e->getMessage());

            return back()->withInput();
        }
    }

    public function show(Pengajuan $pengajuan)
    {
        $this->authorizeUser($pengajuan);
        $pengajuan->load([
            'user',
            'verifiedBy',
            'organisasi',
            'desa.kecamatan',
            'jenisBantuan',
            'logs.user',
        ]);

        $bantuanUangByVerifikasi = collect();
        $bantuanBarangJasaByVerifikasi = collect();

        $desaIdsForLogLabels = collect();
        foreach ($pengajuan->logs as $log) {
            $changes = data_get($log->metadata, 'changes');
            if (! is_array($changes)) {
                continue;
            }
            foreach ($changes as $field => $pair) {
                if ($field !== 'desa_id' || ! is_array($pair)) {
                    continue;
                }
                foreach (['from', 'to'] as $dir) {
                    $v = $pair[$dir] ?? null;
                    if ($v) {
                        $desaIdsForLogLabels->push($v);
                    }
                }
            }
        }
        $desaNamaById = $desaIdsForLogLabels->isNotEmpty()
            ? Desa::query()->whereIn('id', $desaIdsForLogLabels->unique()->all())->pluck('nama', 'id')
            : collect();

        return view('pages.pengajuan.show', compact(
            'pengajuan',
            'bantuanUangByVerifikasi',
            'bantuanBarangJasaByVerifikasi',
            'desaNamaById'
        ));
    }

    public function edit(Pengajuan $pengajuan)
    {
        $this->authorizeUser($pengajuan);
        if (! $pengajuan->canEdit()) {
            toast()->warning('Tidak dapat diedit', 'Pengajuan ini tidak dapat diedit.');

            return redirect()->route('pengajuan.show', $pengajuan);
        }

        $pengajuan->load(['desa.kecamatan', 'details']);

        $jenisUser = auth()->user()->jenis_user?->value ?? null;
        $jenisOptions = match ($jenisUser) {
            'IND' => [JenisPengajuan::BANSOS],
            'KLP' => [JenisPengajuan::BANTUAN_KELOMPOK],
            default => [JenisPengajuan::HIBAH],
        };

        $kelompokList = Organisasi::query()
            ->where('jenis', JenisOrganisasi::KELOMPOK)
            ->where('is_active', true)
            // Kelompok hanya muncul bila dibuat pada/atau sebelum tahun terpilih.
            ->where('tahun_anggaran', '<=', tahun_aktif())
            ->orderBy('nama')
            ->get(['id', 'nama']);
        $pendudukList = Penduduk::query()
            ->orderBy('nama')
            ->get(['id', 'nama', 'nik']);

        $jenisBantuanKelompokList = JenisBantuan::query()
            ->where('kategori', 'bantuan_kelompok')
            ->orderBy('nama')
            ->get(['id', 'nama', 'keterangan']);

        $kecamatans = Kecamatan::query()->with('desa')->orderBy('nama')->get();

        $jenis = $this->jenisPengajuanForUser();
        $organisasiId = auth()->user()->userDetail?->organisasi_id;
        $this->seedRosterKelompok($organisasiId);
        $selectedPendudukId = old('penduduk_id', $pengajuan->details->first()?->penduduk_id);
        $pendudukIsValidMap = $this->pendudukIsValidMap();
        $simpanDiblokir = $this->shouldBlockSimpanPengajuan($jenis, $selectedPendudukId, $organisasiId, $pendudukIsValidMap);
        $kelompokTanpaAnggota = $jenis !== JenisPengajuan::BANSOS->value
            && $organisasiId
            && $this->jumlahAnggotaKelompok($organisasiId) < 1;
        $kelompokSimpanDiblokir = $kelompokTanpaAnggota || ($jenis !== JenisPengajuan::BANSOS->value
            && $organisasiId
            && $this->kelompokMemilikiAnggotaBelumTerverifikasi($organisasiId));

        // Pengajuan yang sedang diedit tidak dihitung sebagai duplikat.
        $kelompokSudahMengajukan = $jenis !== JenisPengajuan::BANSOS->value
            && $organisasiId
            && Pengajuan::pengajuanKelompokTahun($organisasiId, $pengajuan->id) !== null;
        $simpanDiblokir = $simpanDiblokir || $kelompokSudahMengajukan;

        $anggotaBelumTerverifikasi = $organisasiId
            ? (Organisasi::query()->find($organisasiId)?->anggotaBelumTerverifikasiData() ?? collect())
            : collect();

        return view('pages.pengajuan.form', [
            'pengajuan' => $pengajuan,
            'kelompokList' => $kelompokList,
            'pendudukList' => $pendudukList,
            'jenisOptions' => $jenisOptions,
            'jenisBantuanKelompokList' => $jenisBantuanKelompokList,
            'jenis' => $jenis,
            'kecamatans' => $kecamatans,
            'selectedPendudukId' => $selectedPendudukId,
            'pendudukIsValidMap' => $pendudukIsValidMap,
            'simpanDiblokir' => $simpanDiblokir,
            'kelompokSimpanDiblokir' => $kelompokSimpanDiblokir,
            'kelompokTanpaAnggota' => $kelompokTanpaAnggota,
            'kelompokSudahMengajukan' => $kelompokSudahMengajukan,
            'anggotaBelumTerverifikasi' => $anggotaBelumTerverifikasi,
        ]);
    }

    public function update(Request $request, Pengajuan $pengajuan)
    {

        // return $request->all();
        $this->authorizeUser($pengajuan);
        if (! $pengajuan->canEdit()) {
            toast()->warning('Tidak dapat diedit', 'Pengajuan ini tidak dapat diedit.');

            return redirect()->route('pengajuan.index');
        }

        $validated = $this->validatePengajuan($request);
        $this->validatePengajuanVerifikasi($request, $pengajuan);

        try {
            DB::beginTransaction();
            $hadFileBefore = $pengajuan->hasMedia('pengajuan');
            $trackedKeys = ['judul', 'lokasi', 'desa_id', 'nilai'];
            $before = [
                'judul' => $pengajuan->judul,
                'lokasi' => $pengajuan->lokasi,
                'desa_id' => $pengajuan->desa_id,
                'nilai' => $pengajuan->nilai,
            ];

            $pengajuan->jenis_bantuan_id = $validated['jenis_bantuan_id'] ?? null;
            $pengajuan->kategori_pengajuan = JenisPengajuan::from($validated['jenis']);
            $pengajuan->judul = $validated['judul'];
            $pengajuan->nilai = $validated['nilai'];
            $pengajuan->desa_id = $validated['desa_id'] ?? null;
            $pengajuan->lokasi = $validated['lokasi'] ?? null;
            $pengajuan->save();

            if ($request->hasFile('file_pengajuan')) {
                $pengajuan->clearMediaCollection('pengajuan');
                $pengajuan->addMediaFromRequest('file_pengajuan')->toMediaCollection('pengajuan');
            }

            $changes = [];
            foreach ($trackedKeys as $key) {
                $from = $before[$key];
                $to = $pengajuan->getAttribute($key);
                $changed = $key === 'nilai'
                    ? (float) $from != (float) $to
                    : $from !== $to;
                if ($changed) {
                    $changes[$key] = ['from' => $from, 'to' => $to];
                }
            }
            if ($request->hasFile('file_pengajuan')) {
                $changes['file_pengajuan'] = [
                    'from' => $hadFileBefore ? 'berkas_ada' : null,
                    'to' => 'berkas_diunggah',
                ];
            }

            $metadata = $changes !== [] ? ['changes' => $changes] : null;
            $this->logPengajuan($pengajuan, 'updated', $pengajuan->status->value, $pengajuan->status->value, null, $metadata);
            DB::commit();
            toast()->success('Berhasil', 'Pengajuan berhasil diperbarui.');

            return redirect()->route('pengajuan.index');
        } catch (\Throwable $e) {
            DB::rollBack();
            toast()->error('Gagal', $e->getMessage());

            return back()->withInput();
        }
    }

    public function submit(Pengajuan $pengajuan)
    {

        $this->authorizeUser($pengajuan);
        if (! $pengajuan->canSubmit()) {
            toast()->warning('Tidak dapat diajukan', 'Status pengajuan tidak memungkinkan untuk diajukan.');

            return redirect()->route('pengajuan.index');
        }

        // Satu kelompok hanya boleh mengajukan sekali per tahun anggaran.
        $duplikat = $pengajuan->organisasi_id
            ? Pengajuan::pengajuanKelompokTahun($pengajuan->organisasi_id, $pengajuan->id, (int) $pengajuan->tahun_anggaran)
            : null;
        if ($duplikat !== null) {
            toast()->warning('Tidak dapat diajukan', $this->pesanKelompokSudahMengajukan($duplikat));

            return redirect()->route('pengajuan.index');
        }

        $oldStatus = $pengajuan->status->value;

        try {
            DB::beginTransaction();
            $pengajuan->update(['status' => PengajuanStatus::DIAJUKAN]);
            $this->logPengajuan($pengajuan, 'status_changed', $oldStatus, PengajuanStatus::DIAJUKAN->value);
            app(PengajuanSnapshotService::class)->capture($pengajuan, MomenSnapshot::DIAJUKAN);
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            toast()->error('Gagal', 'Pengajuan gagal diajukan.');

            return redirect()->route('pengajuan.index');
        }

        toast()->success('Berhasil', 'Pengajuan berhasil diajukan.');

        return redirect()->route('pengajuan.index');
    }

    private function authorizeUser(Pengajuan $pengajuan): void
    {
        if ($pengajuan->user_id !== auth()->id()) {
            abort(403);
        }
    }

    private function generateKodePengajuan(): string
    {
        do {
            $kode = 'PEN-'.now()->format('Ymd').'-'.strtoupper(Str::random(4));
        } while (Pengajuan::where('kode_pengajuan', $kode)->exists());

        return $kode;
    }

    /**
     * Selaras dengan form: hanya pengguna IND yang memakai BANSOS; KLP/ORG memakai alur kelompok.
     */
    private function jenisPengajuanForUser(): string
    {
        return auth()->user()->jenis_user === JenisUser::INDIVIDUAL
            ? JenisPengajuan::BANSOS->value
            : JenisPengajuan::BANTUAN_KELOMPOK->value;
    }

    /**
     * @param  array<string, bool>  $pendudukIsValidMap
     */
    private function shouldBlockSimpanPengajuan(string $jenis, ?string $selectedPendudukId, ?string $organisasiId, array $pendudukIsValidMap): bool
    {
        if ($jenis === JenisPengajuan::BANSOS->value) {
            if (! $selectedPendudukId) {
                return false;
            }

            return ! ($pendudukIsValidMap[$selectedPendudukId] ?? false);
        }

        if (! $organisasiId) {
            return false;
        }

        return $this->jumlahAnggotaKelompok($organisasiId) < 1
            || $this->kelompokMemilikiAnggotaBelumTerverifikasi($organisasiId);
    }

    /**
     * @return array<string, bool>
     */
    private function pendudukIsValidMap(): array
    {
        return Penduduk::query()
            ->get(['id', 'is_valid'])
            ->mapWithKeys(fn (Penduduk $p) => [$p->id => (bool) $p->is_valid])
            ->all();
    }

    /**
     * Pastikan roster kelompok untuk tahun terpilih sudah ada (copy-on-first-use),
     * sehingga pemeriksaan anggota mengikuti roster tahun anggaran terpilih.
     */
    private function seedRosterKelompok(?string $organisasiId): void
    {
        if (! $organisasiId) {
            return;
        }

        $organisasi = Organisasi::find($organisasiId);
        if ($organisasi) {
            app(RosterKelompokService::class)->ensureSeeded($organisasi, tahun_aktif());
        }
    }

    private function kelompokMemilikiAnggotaBelumTerverifikasi(string $organisasiId): bool
    {
        return OrganisasiDetail::query()
            ->where('organisasi_id', $organisasiId)
            ->whereHas('penduduk', fn ($q) => $q->where('is_valid', false))
            ->exists();
    }

    /**
     * Jumlah anggota kelompok pada roster tahun anggaran aktif.
     */
    private function jumlahAnggotaKelompok(string $organisasiId): int
    {
        return OrganisasiDetail::query()
            ->where('organisasi_id', $organisasiId)
            ->count();
    }

    private function validatePengajuanVerifikasi(Request $request, ?Pengajuan $pengajuanSaatIni = null): void
    {
        $jenis = $this->jenisPengajuanForUser();
        if ($jenis === JenisPengajuan::BANSOS->value) {
            $pendudukId = $request->input('penduduk_id');
            if ($pendudukId && ! Penduduk::query()->whereKey($pendudukId)->where('is_valid', true)->exists()) {
                throw ValidationException::withMessages([
                    'penduduk_id' => ['Penduduk yang dipilih belum terverifikasi.'],
                ]);
            }

            return;
        }

        $organisasiId = $request->input('organisasi_id');
        if (! $organisasiId) {
            return;
        }

        $this->guardKelompokBelumMengajukanTahunIni((string) $organisasiId, $pengajuanSaatIni);

        // Pastikan roster tahun aktif sudah terisi sebelum anggotanya diperiksa.
        $this->seedRosterKelompok($organisasiId);

        if ($this->jumlahAnggotaKelompok($organisasiId) < 1) {
            throw ValidationException::withMessages([
                'organisasi_id' => ['Kelompok yang dipilih belum memiliki anggota. Tambahkan minimal 1 anggota kelompok terlebih dahulu.'],
            ]);
        }

        if ($this->kelompokMemilikiAnggotaBelumTerverifikasi($organisasiId)) {
            throw ValidationException::withMessages([
                'organisasi_id' => ['Masih ada anggota kelompok yang data penduduknya belum diverifikasi.'],
            ]);
        }
    }

    /**
     * Satu kelompok hanya boleh mengajukan sekali dalam satu tahun anggaran.
     * Pengajuan yang sedang diedit dikecualikan supaya tidak menabrak dirinya sendiri.
     */
    private function guardKelompokBelumMengajukanTahunIni(string $organisasiId, ?Pengajuan $pengajuanSaatIni = null): void
    {
        $duplikat = Pengajuan::pengajuanKelompokTahun($organisasiId, $pengajuanSaatIni?->id);
        if ($duplikat === null) {
            return;
        }

        $pesan = $this->pesanKelompokSudahMengajukan($duplikat);
        toast()->warning('Tidak dapat mengajukan', $pesan);

        throw ValidationException::withMessages([
            'organisasi_id' => [$pesan],
        ]);
    }

    private function pesanKelompokSudahMengajukan(Pengajuan $duplikat): string
    {
        return 'Kelompok ini sudah mengajukan bantuan pada tahun anggaran '.(int) $duplikat->tahun_anggaran
            .' (kode '.($duplikat->kode_pengajuan ?? '-').', status '
            .($duplikat->status?->getDescription() ?? '-').'). '
            .'Satu kelompok hanya dapat mengajukan satu kali per tahun anggaran.';
    }

    private function validatePengajuan(Request $request): array
    {
        $rules = [
            'jenis' => ['required', \Illuminate\Validation\Rule::enum(JenisPengajuan::class)],
            'jenis_bantuan_id' => 'nullable|exists:jenis_bantuan,id',
            'judul' => 'required|string|max:255',
            'opd_id' => 'required|exists:opd,id',
            'organisasi_id' => 'required|exists:organisasi,id',
            'lokasi' => 'nullable|string|max:255',
            'nilai' => 'required|numeric|min:0',
            'file_pengajuan' => 'nullable|file|mimes:pdf|max:5120',
            'desa_id' => 'nullable|exists:desa,id',
        ];

        return $request->validate($rules);
    }

    private function logPengajuan(Pengajuan $pengajuan, string $action, ?string $statusFrom, ?string $statusTo, ?string $catatan = null, ?array $metadata = null): void
    {
        PengajuanLog::create([
            'pengajuan_id' => $pengajuan->id,
            'user_id' => auth()->id(),
            'action' => $action,
            'status_from' => $statusFrom,
            'status_to' => $statusTo,
            'catatan' => $catatan,
            'metadata' => $metadata,
        ]);
    }
}
