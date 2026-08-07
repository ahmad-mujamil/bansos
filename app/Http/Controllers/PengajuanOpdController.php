<?php

namespace App\Http\Controllers;

use App\Enums\JenisOrganisasi;
use App\Enums\JenisPenerimaBantuan;
use App\Enums\JenisPengajuan;
use App\Enums\MomenSnapshot;
use App\Enums\PengajuanStatus;
use App\Models\Desa;
use App\Models\JenisBantuan;
use App\Models\Kecamatan;
use App\Models\Organisasi;
use App\Models\OrganisasiDetail;
use App\Models\Penduduk;
use App\Models\Pengajuan;
use App\Models\PengajuanDetail;
use App\Models\PengajuanLog;
use App\Services\PengajuanSnapshotService;
use App\Services\RosterKelompokService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Yajra\DataTables\Facades\DataTables;

class PengajuanOpdController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return $this->data();
        }

        return view('pages.pengajuan-opd.index');
    }

    private function data()
    {
        $statusRequest = (string) request('status', 'all');
        $allowedStatuses = [
            PengajuanStatus::DRAFT->value,
            PengajuanStatus::DIAJUKAN->value,
            PengajuanStatus::DISETUJUI->value,
            PengajuanStatus::DITOLAK->value,
        ];

        $query = Pengajuan::query()
            ->with(['organisasi', 'details.penduduk', 'verifikasiPengajuan'])
            ->where('user_id', Auth::id())
            ->latest();

        if ($statusRequest !== 'all' && in_array($statusRequest, $allowedStatuses, true)) {
            $query->where('status', $statusRequest);
        }

        return DataTables::of($query)
            ->addColumn('kode_pengajuan', fn ($row) => e($row->kode_pengajuan))
            ->addColumn('jenis_bantuan', function ($row) {
                $jp = $row->kategori_pengajuan;
                if ($jp === null) {
                    return '<span class="badge bg-light text-muted">-</span>';
                }
                $warna = match ($jp) {
                    \App\Enums\JenisPengajuan::SUBSIDI_BUNGA => 'bg-warning text-dark',
                    \App\Enums\JenisPengajuan::BANTUAN_KELOMPOK => 'bg-primary',
                    \App\Enums\JenisPengajuan::HIBAH => 'bg-info text-dark',
                    \App\Enums\JenisPengajuan::BANSOS => 'bg-success',
                };

                return '<span class="badge '.$warna.'">'.e($jp->getDescription()).'</span>';
            })
            ->addColumn('pemohon', function ($row) {
                if ($row->organisasi_id) {
                    return '<span class="badge bg-primary me-1">Kelompok</span>'.e($row->organisasi?->nama ?? '-');
                }

                $nama = $row->details->first()?->penduduk?->nama;

                return '<span class="badge bg-secondary me-1">Individu</span>'.e($nama ?? '-');
            })
            ->addColumn('judul', fn ($row) => e($row->judul ?? '-'))
            ->addColumn('status', function ($row) {
                $status = $row->status;
                $badge = $status?->badgeColor() ?? 'secondary';

                return '<span class="badge bg-'.$badge.'">'.e($status?->getDescription() ?? '-').'</span>';
            })
            ->addColumn('tanggal', fn ($row) => $row->created_at?->translatedFormat('d M Y') ?? '-')
            ->addColumn('action', function ($row) {
                $csrf = csrf_token();
                $show = route('pengajuan-opd.show', $row->id);
                $html = "<div class='d-flex gap-1'>";
                $html .= "<a href='{$show}' class='btn btn-sm btn-outline-primary'>Lihat</a>";

                if ($row->canEdit()) {
                    $edit = route('pengajuan-opd.edit', $row->id);
                    $html .= "<a href='{$edit}' class='btn btn-sm btn-outline-secondary'>Edit</a>";
                }

                if ($row->canSubmit()) {
                    $submit = route('pengajuan-opd.submit', $row->id);
                    $html .= "<form action='{$submit}' method='POST' class='form-ajukan-pengajuan'>"
                        ."<input type='hidden' name='_token' value='{$csrf}'>"
                        ."<button type='submit' class='btn btn-sm btn-success'>Ajukan</button>"
                        ."</form>";
                }

                if ($row->canDelete()) {
                    $destroy = route('pengajuan-opd.destroy', $row->id);
                    $html .= "<form action='{$destroy}' method='POST' class='form-hapus-pengajuan'>"
                        ."<input type='hidden' name='_token' value='{$csrf}'>"
                        ."<input type='hidden' name='_method' value='DELETE'>"
                        ."<button type='submit' class='btn btn-sm btn-danger'>Hapus</button>"
                        ."</form>";
                }

                $html .= '</div>';

                return $html;
            })
            ->rawColumns(['jenis_bantuan', 'pemohon', 'status', 'action'])
            ->toJson();
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
        $this->seedRosterKelompok($selectedOrganisasiId);
        $kelompokSimpanDiblokir = $selectedOrganisasiId
            ? $this->kelompokMemilikiAnggotaBelumTerverifikasi($selectedOrganisasiId)
            : false;

        $anggotaBelumTerverifikasi = $selectedOrganisasiId
            ? (Organisasi::query()->find($selectedOrganisasiId)?->anggotaBelumTerverifikasiData() ?? collect())
            : collect();

        $pendudukList = Penduduk::query()
            ->orderBy('nama')
            ->get(['id', 'nama', 'nik', 'is_valid']);

        $pendudukIsValidMap = $pendudukList
            ->mapWithKeys(fn (Penduduk $p) => [$p->id => (bool) $p->is_valid])
            ->all();

        $jenisPenerimaKey = old('jenis_penerima_bantuan', JenisPenerimaBantuan::NON_INDIVIDU->value);
        $jenisPenerimaEnum = JenisPenerimaBantuan::tryFrom($jenisPenerimaKey);
        $selectedPendudukIndividuId = old('penduduk_id');
        $pendudukIndividuBelumValid = false;
        if (
            $jenisPenerimaEnum !== null
            && in_array($jenisPenerimaEnum, [JenisPenerimaBantuan::INDIVIDU, JenisPenerimaBantuan::KELUARGA], true)
            && $selectedPendudukIndividuId
        ) {
            $pendudukIndividuBelumValid = ! (bool) Penduduk::query()->whereKey($selectedPendudukIndividuId)->value('is_valid');
        }
        $simpanDiblokir = $kelompokSimpanDiblokir || $pendudukIndividuBelumValid;
        $detailPendudukForIndividu = null;

        return view('pages.pengajuan-opd.form', [
            'pengajuan' => null,
            'pendudukList' => $pendudukList,
            'pendudukIsValidMap' => $pendudukIsValidMap,
            'kelompokList' => $kelompokList,
            'jenisBantuanKelompokList' => $jenisBantuanKelompokList,
            'jenis' => $jenis,
            'kecamatans' => $kecamatans,
            'simpanDiblokir' => $simpanDiblokir,
            'kelompokSimpanDiblokir' => $kelompokSimpanDiblokir,
            'anggotaBelumTerverifikasi' => $anggotaBelumTerverifikasi,
            'detailPendudukForIndividu' => $detailPendudukForIndividu,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $this->validatePengajuan($request);
        $this->validatePengajuanVerifikasi($request);

        $jenisPenerima = JenisPenerimaBantuan::from($validated['jenis_penerima_bantuan']);
        $isIndividuKeluarga = in_array($jenisPenerima, [JenisPenerimaBantuan::INDIVIDU, JenisPenerimaBantuan::KELUARGA], true);

        try {
            DB::beginTransaction();
            $pengajuan = new Pengajuan;
            $pengajuan->user_id = Auth::id();
            $pengajuan->kode_pengajuan = $this->generateKodePengajuan();
            $pengajuan->jenis_bantuan_id = $validated['jenis_bantuan_id'] ?? null;
            $pengajuan->kategori_pengajuan = JenisPengajuan::from($validated['jenis']);
            $pengajuan->judul = $validated['judul'];
            $pengajuan->lokasi = $validated['lokasi'] ?? null;
            $pengajuan->nilai = $validated['nilai'];
            $pengajuan->opd_id = Auth::user()?->opd_id;
            $pengajuan->organisasi_id = $isIndividuKeluarga ? null : $validated['organisasi_id'];
            $pengajuan->jenis_penerima_bantuan = $jenisPenerima;
            $pengajuan->desa_id = $validated['desa_id'] ?? null;
            $pengajuan->status = PengajuanStatus::DRAFT;
            $pengajuan->is_pengajuan_opd = true;
            $pengajuan->save();

            if ($isIndividuKeluarga) {
                PengajuanDetail::query()->create([
                    'pengajuan_id' => $pengajuan->id,
                    'penduduk_id' => $validated['penduduk_id'],
                    'nilai' => null,
                ]);
            }

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
            'details.penduduk',
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
        $pengajuan->load(['desa.kecamatan', 'details.penduduk']);

        // Pertahankan jenis asli pengajuan (mis. subsidi_bunga) agar tidak berubah saat diedit.
        $jenis = $pengajuan->kategori_pengajuan?->value ?? JenisPengajuan::BANTUAN_KELOMPOK->value;
        $jenisOptions = [$pengajuan->kategori_pengajuan ?? JenisPengajuan::BANTUAN_KELOMPOK];

        $kelompokList = $this->kelompokListForOpd($opdId, $jenis, $pengajuan->organisasi_id);

        $jenisBantuanKelompokList = JenisBantuan::query()
            ->where('kategori', 'bantuan_kelompok')
            ->orderBy('nama')
            ->get(['id', 'nama', 'keterangan']);

        $kecamatans = Kecamatan::query()->with('desa')->orderBy('nama')->get();

        $selectedOrganisasiId = old('organisasi_id', $pengajuan->organisasi_id);
        $this->seedRosterKelompok($selectedOrganisasiId);
        $kelompokSimpanDiblokir = $selectedOrganisasiId
            ? $this->kelompokMemilikiAnggotaBelumTerverifikasi($selectedOrganisasiId)
            : false;

        $anggotaBelumTerverifikasi = $selectedOrganisasiId
            ? (Organisasi::query()->find($selectedOrganisasiId)?->anggotaBelumTerverifikasiData() ?? collect())
            : collect();

        $pendudukList = Penduduk::query()
            ->orderBy('nama')
            ->get(['id', 'nama', 'nik', 'is_valid']);

        $pendudukIsValidMap = $pendudukList
            ->mapWithKeys(fn (Penduduk $p) => [$p->id => (bool) $p->is_valid])
            ->all();

        $defaultJenisPenerima = $pengajuan->jenis_penerima_bantuan?->value
            ?? JenisPenerimaBantuan::NON_INDIVIDU->value;
        $jenisPenerimaKey = old('jenis_penerima_bantuan', $defaultJenisPenerima);
        $jenisPenerimaEnum = JenisPenerimaBantuan::tryFrom($jenisPenerimaKey);
        $detailPendudukForIndividu = $pengajuan->details->first()?->penduduk;
        $selectedPendudukIndividuId = old('penduduk_id', $detailPendudukForIndividu?->id);
        $pendudukIndividuBelumValid = false;
        if (
            $jenisPenerimaEnum !== null
            && in_array($jenisPenerimaEnum, [JenisPenerimaBantuan::INDIVIDU, JenisPenerimaBantuan::KELUARGA], true)
            && $selectedPendudukIndividuId
        ) {
            $pendudukIndividuBelumValid = ! (bool) Penduduk::query()->whereKey($selectedPendudukIndividuId)->value('is_valid');
        }
        $simpanDiblokir = $kelompokSimpanDiblokir || $pendudukIndividuBelumValid;

        return view('pages.pengajuan-opd.form', [
            'pengajuan' => $pengajuan,
            'kelompokList' => $kelompokList,
            'jenisOptions' => $jenisOptions,
            'jenisBantuanKelompokList' => $jenisBantuanKelompokList,
            'jenis' => $jenis,
            'kecamatans' => $kecamatans,
            'simpanDiblokir' => $simpanDiblokir,
            'kelompokSimpanDiblokir' => $kelompokSimpanDiblokir,
            'anggotaBelumTerverifikasi' => $anggotaBelumTerverifikasi,
            'pendudukList' => $pendudukList,
            'pendudukIsValidMap' => $pendudukIsValidMap,
            'detailPendudukForIndividu' => $detailPendudukForIndividu,
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
            $jenisPenerima = JenisPenerimaBantuan::from($validated['jenis_penerima_bantuan']);
            $isIndividuKeluarga = in_array($jenisPenerima, [JenisPenerimaBantuan::INDIVIDU, JenisPenerimaBantuan::KELUARGA], true);

            $trackedKeys = ['judul', 'lokasi', 'desa_id', 'nilai', 'organisasi_id', 'jenis_penerima_bantuan'];
            $before = [
                'judul' => $pengajuan->judul,
                'lokasi' => $pengajuan->lokasi,
                'desa_id' => $pengajuan->desa_id,
                'nilai' => $pengajuan->nilai,
                'organisasi_id' => $pengajuan->organisasi_id,
                'jenis_penerima_bantuan' => $pengajuan->jenis_penerima_bantuan?->value,
            ];

            $pengajuan->jenis_bantuan_id = $validated['jenis_bantuan_id'] ?? null;
            $pengajuan->kategori_pengajuan = JenisPengajuan::from($validated['jenis']);
            $pengajuan->judul = $validated['judul'];
            $pengajuan->nilai = $validated['nilai'];
            $pengajuan->desa_id = $validated['desa_id'] ?? null;
            $pengajuan->lokasi = $validated['lokasi'] ?? null;
            $pengajuan->organisasi_id = $isIndividuKeluarga ? null : $validated['organisasi_id'];
            $pengajuan->jenis_penerima_bantuan = $jenisPenerima;
            $pengajuan->is_pengajuan_opd = true;
            $pengajuan->save();

            $pengajuan->details()->delete();
            if ($isIndividuKeluarga) {
                PengajuanDetail::query()->create([
                    'pengajuan_id' => $pengajuan->id,
                    'penduduk_id' => $validated['penduduk_id'],
                    'nilai' => null,
                ]);
            }

            if ($request->hasFile('file_pengajuan')) {
                $pengajuan->clearMediaCollection('pengajuan');
                $pengajuan->addMediaFromRequest('file_pengajuan')->toMediaCollection('pengajuan');
            }

            $changes = [];
            foreach ($trackedKeys as $key) {
                $from = $before[$key];
                $to = $key === 'jenis_penerima_bantuan'
                    ? $pengajuan->jenis_penerima_bantuan?->value
                    : $pengajuan->getAttribute($key);
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

        try {
            DB::beginTransaction();
            $pengajuan->update(['status' => PengajuanStatus::DIAJUKAN]);
            $this->logPengajuan($pengajuan, 'status_changed', $oldStatus, PengajuanStatus::DIAJUKAN->value);
            app(PengajuanSnapshotService::class)->capture($pengajuan, MomenSnapshot::DIAJUKAN);
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            toast()->error('Gagal', 'Pengajuan OPD gagal diajukan.');

            return redirect()->route('pengajuan-opd.index');
        }

        toast()->success('Berhasil', 'Pengajuan OPD berhasil diajukan.');

        return redirect()->route('pengajuan-opd.index');
    }

    public function destroy(Pengajuan $pengajuan)
    {
        $this->authorizeUser($pengajuan);
        if (! $pengajuan->canDelete()) {
            toast()->warning('Tidak dapat dihapus', 'Pengajuan yang sudah diverifikasi tidak dapat dihapus.');

            return redirect()->route('pengajuan-opd.index');
        }

        try {
            DB::beginTransaction();
            $pengajuan->details()->delete();
            $pengajuan->clearMediaCollection('pengajuan');
            $pengajuan->delete();
            DB::commit();
            toast()->success('Berhasil', 'Pengajuan OPD berhasil dihapus.');
        } catch (\Throwable $e) {
            DB::rollBack();
            toast()->error('Gagal', $e->getMessage());
        }

        return redirect()->route('pengajuan-opd.index');
    }

    /**
     * Select2: cari penduduk berdasarkan NIK atau nama (minimal 2 karakter).
     */
    public function searchPenduduk(Request $request): JsonResponse
    {
        $term = $request->string('q')->trim()->value();
        if (mb_strlen($term) < 2) {
            return response()->json(['results' => []]);
        }

        $penduduk = Penduduk::query()
            ->where(function ($q) use ($term) {
                $q->where('nik', 'like', '%'.$term.'%')
                    ->orWhere('nama', 'like', '%'.$term.'%');
            })
            ->orderBy('nama')
            ->limit(30)
            ->get(['id', 'nama', 'nik', 'is_valid']);

        $results = $penduduk->map(fn (Penduduk $p) => [
            'id' => $p->id,
            'text' => $p->nama.' — '.$p->nik,
            'nama' => $p->nama,
            'nik' => $p->nik,
            'is_valid' => (bool) $p->is_valid,
        ])->all();

        return response()->json(['results' => $results]);
    }

    private function kelompokListForOpd(string $opdId, ?string $jenis, ?string $alwaysIncludeOrganisasiId = null)
    {

        $jenis_pengajuan = JenisPengajuan::tryFrom($jenis);
        if ($jenis_pengajuan === null) {
            return [];
        }
        $jenisOrganisasiValues = array_map(
            static fn (JenisOrganisasi $o) => $o->value,
            $jenis_pengajuan->getJenisOrganisasi(),
        );

        return Organisasi::query()
            ->where('is_active', true)
            ->where('is_blacklist', false)
            // ->where('opd_id', $opdId)
            ->whereIn('jenis', $jenisOrganisasiValues)
            // Kelompok hanya muncul bila dibuat pada/atau sebelum tahun terpilih.
            ->where('tahun_anggaran', '<=', tahun_aktif())
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
            $kode = 'PEN-'.now()->format('Ymd').'-'.strtoupper(Str::random(4));
        } while (Pengajuan::where('kode_pengajuan', $kode)->exists());

        return $kode;
    }

    private function kelompokMemilikiAnggotaBelumTerverifikasi(string $organisasiId): bool
    {
        return OrganisasiDetail::query()
            ->where('organisasi_id', $organisasiId)
            ->whereHas('penduduk', fn ($q) => $q->where('is_valid', false))
            ->exists();
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

    private function validatePengajuanVerifikasi(Request $request): void
    {
        $jenisPenerima = JenisPenerimaBantuan::tryFrom((string) $request->input('jenis_penerima_bantuan'));

        if ($jenisPenerima === JenisPenerimaBantuan::NON_INDIVIDU) {
            $organisasiId = (string) $request->input('organisasi_id');
            if ($organisasiId !== '' && $this->kelompokMemilikiAnggotaBelumTerverifikasi($organisasiId)) {
                throw ValidationException::withMessages([
                    'organisasi_id' => ['Masih ada anggota kelompok yang data penduduknya belum diverifikasi.'],
                ]);
            }

            return;
        }

        if (in_array($jenisPenerima, [JenisPenerimaBantuan::INDIVIDU, JenisPenerimaBantuan::KELUARGA], true)) {
            $pendudukId = (string) $request->input('penduduk_id');
            if ($pendudukId === '') {
                return;
            }
            $isValid = (bool) Penduduk::query()->whereKey($pendudukId)->value('is_valid');
            if (! $isValid) {
                throw ValidationException::withMessages([
                    'penduduk_id' => ['Penduduk yang dipilih belum terverifikasi (is_valid).'],
                ]);
            }
        }
    }

    /**
     * @return array<int, string>
     */
    private function jenisOrganisasiValuesForPengajuan(?string $jenis): array
    {
        $jenisPengajuan = JenisPengajuan::tryFrom((string) $jenis);
        if ($jenisPengajuan === null) {
            return [];
        }

        return array_map(
            static fn (JenisOrganisasi $o) => $o->value,
            $jenisPengajuan->getJenisOrganisasi(),
        );
    }

    private function validatePengajuan(Request $request): array
    {
        $opdId = Auth::user()?->opd_id;

        $jenisOrgValues = $this->jenisOrganisasiValuesForPengajuan($request->input('jenis'));

        return $request->validate([
            'jenis' => ['required', Rule::enum(JenisPengajuan::class)],
            'jenis_penerima_bantuan' => ['required', Rule::enum(JenisPenerimaBantuan::class)],
            'jenis_bantuan_id' => 'nullable|exists:jenis_bantuan,id',
            'judul' => 'required|string|max:255',
            'organisasi_id' => [
                Rule::requiredIf(fn () => $request->input('jenis_penerima_bantuan') === JenisPenerimaBantuan::NON_INDIVIDU->value),
                'nullable',
                'uuid',
                Rule::exists('organisasi', 'id')->where(function ($q) use ($opdId, $jenisOrgValues) {
                    $q->where('opd_id', $opdId);
                    if ($jenisOrgValues !== []) {
                        $q->whereIn('jenis', $jenisOrgValues);
                    }
                }),
            ],
            'penduduk_id' => [
                Rule::requiredIf(fn () => in_array($request->input('jenis_penerima_bantuan'), [
                    JenisPenerimaBantuan::INDIVIDU->value,
                    JenisPenerimaBantuan::KELUARGA->value,
                ], true)),
                'nullable',
                'uuid',
                'exists:penduduk,id',
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
