<?php

namespace App\Http\Controllers;

use App\Enums\JenisOrganisasi;
use App\Enums\JenisPengajuan;
use App\Enums\PengajuanStatus;
use App\Models\Desa;
use App\Models\JenisBantuan;
use App\Models\Kecamatan;
use App\Models\Organisasi;
use App\Models\OrganisasiDetail;
use App\Models\Penduduk;
use App\Models\Pengajuan;
use App\Models\PengajuanLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class PengajuanOpdController extends Controller
{
    public function index()
    {
        $pengajuan = Pengajuan::query()
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('pages.pengajuan-opd.index', compact('pengajuan'));
    }

    public function create(Request $request)
    {
        $user = Auth::user();
        $opdId = $user->opd_id;

        $jenis = $request->input('jenis');

         $kelompokList = $this->kelompokListForOpd($opdId, $jenis);

        $jenisBantuanKelompokList = JenisBantuan::query()
            ->where('kategori', 'bantuan_kelompok')
            ->orderBy('nama')
            ->get(['id', 'nama', 'keterangan']);

        $kecamatans = Kecamatan::query()->with('desa')->orderBy('nama')->get();

        $selectedOrganisasiId = old('organisasi_id');
        $simpanDiblokir = $selectedOrganisasiId
            ? $this->kelompokMemilikiAnggotaBelumTerverifikasi($selectedOrganisasiId)
            : false;

        $anggotaBelumTerverifikasi = $selectedOrganisasiId
            ? (Organisasi::query()->find($selectedOrganisasiId)?->anggotaBelumTerverifikasiData() ?? collect())
            : collect();

        $pendudukList = Penduduk::query()
            ->orderBy('nama')
            ->get(['id', 'nama', 'nik']);

        return view('pages.pengajuan-opd.form', [
            'pengajuan' => null,
            'pendudukList' => $pendudukList,
            'kelompokList' => $kelompokList,
            'jenisBantuanKelompokList' => $jenisBantuanKelompokList,
            'jenis' => $jenis,
            'kecamatans' => $kecamatans,
            'simpanDiblokir' => $simpanDiblokir,
            'anggotaBelumTerverifikasi' => $anggotaBelumTerverifikasi,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $this->validatePengajuan($request);
        $this->validatePengajuanVerifikasi($request);

        try {
            DB::beginTransaction();
            $pengajuan = new Pengajuan;
            $pengajuan->user_id = Auth::id();
            $pengajuan->kode_pengajuan = $this->generateKodePengajuan();
            $pengajuan->jenis_bantuan_id = $validated['jenis_bantuan_id'] ?? null;
            $pengajuan->judul = $validated['judul'];
            $pengajuan->lokasi = $validated['lokasi'] ?? null;
            $pengajuan->nilai = $validated['nilai'];
            $pengajuan->opd_id = Auth::user()?->opd_id;
            $pengajuan->organisasi_id = $validated['organisasi_id'];
            $pengajuan->desa_id = $validated['desa_id'] ?? null;
            $pengajuan->status = PengajuanStatus::DRAFT;
            $pengajuan->is_pengajuan_opd = true;
            $pengajuan->save();

            if ($request->hasFile('file_pengajuan')) {
                $pengajuan->addMediaFromRequest('file_pengajuan')->toMediaCollection('pengajuan');
            }

            $this->logPengajuan($pengajuan, 'created', null, PengajuanStatus::DRAFT->value);
            DB::commit();
            toast()->success('Berhasil', 'Pengajuan OPD berhasil disimpan.');

            return redirect()->route('pengajuan-opd.index');
        } catch (\Throwable $e) {
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

        return view('pages.pengajuan-opd.show', compact(
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

            return redirect()->route('pengajuan-opd.show', $pengajuan);
        }

        $user = Auth::user();
        $opdId = $user->opd_id;
        $pengajuan->load(['desa.kecamatan', 'details']);

        $jenis = JenisPengajuan::BANTUAN_KELOMPOK->value;
        $jenisOptions = [JenisPengajuan::BANTUAN_KELOMPOK];

        $kelompokList = $this->kelompokListForOpd($opdId, $jenis, $pengajuan->organisasi_id);

        $jenisBantuanKelompokList = JenisBantuan::query()
            ->where('kategori', 'bantuan_kelompok')
            ->orderBy('nama')
            ->get(['id', 'nama', 'keterangan']);

        $kecamatans = Kecamatan::query()->with('desa')->orderBy('nama')->get();

        $selectedOrganisasiId = old('organisasi_id', $pengajuan->organisasi_id);
        $simpanDiblokir = $selectedOrganisasiId
            ? $this->kelompokMemilikiAnggotaBelumTerverifikasi($selectedOrganisasiId)
            : false;

        $anggotaBelumTerverifikasi = $selectedOrganisasiId
            ? (Organisasi::query()->find($selectedOrganisasiId)?->anggotaBelumTerverifikasiData() ?? collect())
            : collect();

        $pendudukList = Penduduk::query()
            ->orderBy('nama')
            ->get(['id', 'nama', 'nik']);

        return view('pages.pengajuan-opd.form', [
            'pengajuan' => $pengajuan,
            'kelompokList' => $kelompokList,
            'jenisOptions' => $jenisOptions,
            'jenisBantuanKelompokList' => $jenisBantuanKelompokList,
            'jenis' => $jenis,
            'kecamatans' => $kecamatans,
            'simpanDiblokir' => $simpanDiblokir,
            'anggotaBelumTerverifikasi' => $anggotaBelumTerverifikasi,
            'pendudukList' => $pendudukList,
        ]);
    }

    public function update(Request $request, Pengajuan $pengajuan)
    {
        $this->authorizeUser($pengajuan);
        if (! $pengajuan->canEdit()) {
            toast()->warning('Tidak dapat diedit', 'Pengajuan ini tidak dapat diedit.');

            return redirect()->route('pengajuan-opd.index');
        }

        $validated = $this->validatePengajuan($request);
        $this->validatePengajuanVerifikasi($request);

        try {
            DB::beginTransaction();
            $hadFileBefore = $pengajuan->hasMedia('pengajuan');
            $trackedKeys = ['judul', 'lokasi', 'desa_id', 'nilai', 'organisasi_id'];
            $before = [
                'judul' => $pengajuan->judul,
                'lokasi' => $pengajuan->lokasi,
                'desa_id' => $pengajuan->desa_id,
                'nilai' => $pengajuan->nilai,
                'organisasi_id' => $pengajuan->organisasi_id,
            ];

            $pengajuan->jenis_bantuan_id = $validated['jenis_bantuan_id'] ?? null;
            $pengajuan->judul = $validated['judul'];
            $pengajuan->nilai = $validated['nilai'];
            $pengajuan->desa_id = $validated['desa_id'] ?? null;
            $pengajuan->lokasi = $validated['lokasi'] ?? null;
            $pengajuan->organisasi_id = $validated['organisasi_id'];
            $pengajuan->is_pengajuan_opd = true;
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
            toast()->success('Berhasil', 'Pengajuan OPD berhasil diperbarui.');

            return redirect()->route('pengajuan-opd.index');
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

            return redirect()->route('pengajuan-opd.index');
        }

        $oldStatus = $pengajuan->status->value;
        $pengajuan->update(['status' => PengajuanStatus::DIAJUKAN]);
        $this->logPengajuan($pengajuan, 'status_changed', $oldStatus, PengajuanStatus::DIAJUKAN->value);
        toast()->success('Berhasil', 'Pengajuan OPD berhasil diajukan.');

        return redirect()->route('pengajuan-opd.index');
    }


    private function kelompokListForOpd(string $opdId, ?string $jenis, ?string $alwaysIncludeOrganisasiId = null)
    {

        $jenis_pengajuan = JenisPengajuan::tryFrom($jenis);
        if ($jenis_pengajuan === null) {
            return [];
        }
        $jenisOrganisasiValues = $jenis_pengajuan->getJenisOrganisasi();
        return Organisasi::query()
            ->where('is_active', true)
            ->where('is_blacklist', false)
            ->where('opd_id', $opdId)
            ->whereIn('jenis', $jenisOrganisasiValues)
            ->orderBy('nama')
            ->get(['id', 'nama']);
    }

    private function authorizeUser(Pengajuan $pengajuan): void
    {
        $user = Auth::user();
        if (
            $pengajuan->user_id !== $user->id ||
            (string) $pengajuan->opd_id !== (string) $user->opd_id
        ) {
            abort(403);
        }
    }

    private function generateKodePengajuan(): string
    {
        do {
            $kode = 'PEN-' . now()->format('Ymd') . '-' . strtoupper(Str::random(4));
        } while (Pengajuan::where('kode_pengajuan', $kode)->exists());

        return $kode;
    }

    private function kelompokMemilikiAnggotaBelumTerverifikasi(string $organisasiId): bool
    {
        return OrganisasiDetail::query()
            ->where('organisasi_id', $organisasiId)
            ->whereHas('penduduk', fn($q) => $q->where('is_valid', false))
            ->exists();
    }

    private function validatePengajuanVerifikasi(Request $request): void
    {
        $organisasiId = (string) $request->input('organisasi_id');
        if ($organisasiId !== '' && $this->kelompokMemilikiAnggotaBelumTerverifikasi($organisasiId)) {
            throw ValidationException::withMessages([
                'organisasi_id' => ['Masih ada anggota kelompok yang data penduduknya belum diverifikasi.'],
            ]);
        }
    }

    private function validatePengajuan(Request $request): array
    {
        $opdId = Auth::user()?->opd_id;

        $jenisOrgValues = $this->jenisOrganisasiValuesForPengajuan($request->input('jenis'));

        return $request->validate([
            'jenis_bantuan_id' => 'nullable|exists:jenis_bantuan,id',
            'judul' => 'required|string|max:255',
            'organisasi_id' => [
                'required',
                Rule::exists('organisasi', 'id')->where(function ($q) use ($opdId, $jenisOrgValues) {
                    $q->where('opd_id', $opdId);
                    if ($jenisOrgValues !== []) {
                        $q->whereIn('jenis', $jenisOrgValues);
                    }
                }),
            ],
            'lokasi' => 'nullable|string|max:255',
            'nilai' => 'required|numeric|min:0',
            'file_pengajuan' => 'nullable|file|mimes:pdf|max:5120',
            'desa_id' => 'nullable|exists:desa,id',
        ]);
    }

    private function logPengajuan(Pengajuan $pengajuan, string $action, ?string $statusFrom, ?string $statusTo, ?string $catatan = null, ?array $metadata = null): void
    {
        PengajuanLog::create([
            'pengajuan_id' => $pengajuan->id,
            'user_id' => Auth::id(),
            'action' => $action,
            'status_from' => $statusFrom,
            'status_to' => $statusTo,
            'catatan' => $catatan,
            'metadata' => $metadata,
        ]);
    }
}
