<?php

namespace App\Http\Controllers;

use App\Enums\JabatanOrganisasi;
use App\Enums\JenisKelamin;
use App\Enums\JenisPengajuan;
use App\Enums\JenisOrganisasi;
use App\Models\JenisKelompokMasyarakat;
use App\Http\Requests\KelompokMasyarakatRequest;
use App\Models\Kecamatan;
use App\Models\Organisasi;
use App\Models\OrganisasiDetail;
use App\Models\OrganisasiDokumen;
use App\Models\Penduduk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class KelompokMasyarakatController extends Controller
{
    /**
     * Pencarian penduduk berdasarkan NIK (16 digit) untuk pengisian otomatis form anggota.
     */
    public function lookupPendudukByNik(Request $request): \Illuminate\Http\JsonResponse
    {
        $validated = $request->validate([
            'nik' => ['required', 'string', 'regex:/^[0-9]{16}$/'],
        ]);
        $nik = $validated['nik'];
        $penduduk = Penduduk::query()->where('nik', $nik)->first();
        if (! $penduduk) {
            return response()->json(['found' => false]);
        }

        $jk = $penduduk->jk instanceof JenisKelamin ? $penduduk->jk->value : $penduduk->jk;

        return response()->json([
            'found' => true,
            'penduduk' => [
                'id' => $penduduk->id,
                'nik' => $penduduk->nik,
                'nama' => $penduduk->nama,
                'jk' => $jk,
                'kecamatan_id' => $penduduk->kecamatan_id,
                'desa_id' => $penduduk->desa_id,
                'is_valid' => (bool) $penduduk->is_valid,
                'validated_at' => $penduduk->validated_at?->toIso8601String(),
            ],
        ]);
    }

    /**
     * Menentukan penduduk untuk satu baris anggota sebelum menyimpan organisasi_detail:
     *
     * 1. Jika NIK sudah terdaftar di tabel penduduk → gunakan record tersebut (penduduk_id yang sudah ada).
     * 2. Jika NIK belum ada → simpan penduduk baru sesuai data form (nama, jk, kecamatan_id, desa_id, dll.),
     *    lalu gunakan penduduk_id hasil insert tersebut.
     *
     * OrganisasiDetail selalu memakai `$penduduk->id` dari salah satu jalur di atas.
     *
     * @param  array<string, mixed>  $row  Baris tervalidasi dari `anggota.*` (boleh berisi organisasi_detail_id, jabatan; tidak dipakai di sini).
     */
    private function resolveOrCreatePendudukForAnggota(array $row): Penduduk
    {
        $pendudukByNik = Penduduk::query()->where('nik', $row['nik'])->first();

        if ($pendudukByNik !== null) {
            return $pendudukByNik;
        }

        return Penduduk::query()->create([
            'nik' => $row['nik'],
            'nama' => $row['nama'],
            'jk' => $row['jk'],
            'kecamatan_id' => $row['kecamatan_id'],
            'desa_id' => $row['desa_id'],
            'alamat' => '-',
        ]);
    }

    private function getQuery()
    {
        return Organisasi::query()
            ->with(['kecamatan', 'desa', 'opd'])
            ->withCount(['organisasiDetail', 'organisasiDokumen'])
            ->where('opd_id', auth()->user()->opd_id ?? null)
            ->latest();
    }

    private function data(): \Illuminate\Http\JsonResponse
    {
        $data = $this->getQuery();

        return DataTables::of($data)
            ->addColumn('nomor_tgl', fn ($row) => ($row->nomor ?? '-').' / '.($row->tgl_pembentukan?->format('d-m-Y') ?? '-'))
            ->addColumn('wilayah', fn ($row) => ($row->kecamatan->nama ?? '-').' / '.($row->desa->nama ?? '-'))
            ->addColumn('status', fn ($row) => $row->is_active ? 'Aktif' : 'Nonaktif')
            ->addColumn('anggota', function ($row) {
                $count = (int) ($row->organisasi_detail_count ?? 0);
                $url = route('kelompok-masyarakat.anggota.index', $row->id);

                return '<a href="'.$url.'" title="Lihat Anggota ('.$count.' orang)" class="btn btn-sm btn-outline-primary btn-icon btn-icon-start">'
                    .'<i data-acorn-icon="user"></i><span>Anggota ('.$count.')</span></a>';
            })
            ->addColumn('dokumen', function ($row) {
                $count = (int) ($row->organisasi_dokumen_count ?? 0);
                $url = route('kelompok-masyarakat.dokumen.index', $row->id);

                return '<a href="'.$url.'" title="Lihat Dokumen ('.$count.' file)" class="btn btn-sm btn-outline-secondary btn-icon btn-icon-start">'
                    .'<i data-acorn-icon="file"></i><span>Dokumen ('.$count.')</span></a>';
            })
            ->addColumn('action', function ($row) {
                $navActionStart = '<nav class="breadcrumb-container d-inline-block" aria-label="breadcrumb"><ul class="breadcrumb pt-0">';
                $navActionEnd = '</ul></nav>';

                $edit = "<li class='breadcrumb-item'><a href='".route('kelompok-masyarakat.edit', $row->id)."' title='Edit Data' class='fw-bold text-success'>Edit</a></li>";
                $delete = "<li class='breadcrumb-item'><a href='".route('kelompok-masyarakat.destroy', $row->id)."' data-confirm-delete='true' title='Hapus Data' class='fw-bold text-danger'>Delete</a></li>";

                return $navActionStart.$edit.$delete.$navActionEnd;
            })
            ->rawColumns(['anggota', 'dokumen', 'action'])
            ->toJson();
    }

    public function index()
    {
        confirmDelete('Hapus Data', 'Apakah Anda yakin ingin menghapus data ini?');
        if (request()->ajax()) {
            return $this->data();
        }

        return view('pages.kelompok-masyarakat.index');
    }

    public function create(Request $request)
    {
        $organisasi = null;
        $kecamatans = Kecamatan::query()->with('desa')->orderBy('nama')->get();
        $anggotaInitial = old('anggota');
        if ($anggotaInitial === null) {
            $anggotaInitial = [];
        }
        $dokumenInitial = old('dokumen');
        if ($dokumenInitial === null) {
            $dokumenInitial = [];
        }

        $jenisQuery = (string) $request->query('jenis');
        $jenisPengajuan = JenisPengajuan::tryFrom($jenisQuery);
        $jenisPengajuanValue = $jenisPengajuan?->value;
        $isBantuanKelompok = $jenisPengajuan === JenisPengajuan::BANTUAN_KELOMPOK;
        $jenisKelompokMasyarakatOptions = $isBantuanKelompok
            ? JenisKelompokMasyarakat::query()->orderBy('nama')->get()
            : collect();

        $requireJenisSelection = false;

        if ($jenisPengajuan instanceof JenisPengajuan) {
            $jenisOrganisasiOptions = $jenisPengajuan->getJenisOrganisasi();
            $requireJenisSelection = count($jenisOrganisasiOptions) > 1;
            $defaultJenis = $requireJenisSelection ? null : ($jenisOrganisasiOptions[0]?->value ?? null);
        } else {
            $jenisOrganisasiOptions = JenisOrganisasi::cases();
            $defaultJenis = JenisOrganisasi::tryFrom($jenisQuery)?->value;
        }

        return view('pages.kelompok-masyarakat.create', compact(
            'organisasi',
            'kecamatans',
            'anggotaInitial',
            'dokumenInitial',
            'jenisOrganisasiOptions',
            'defaultJenis',
            'requireJenisSelection',
            'jenisPengajuanValue',
            'isBantuanKelompok',
            'jenisKelompokMasyarakatOptions',
        ));
    }

    public function store(KelompokMasyarakatRequest $request): ?\Illuminate\Http\RedirectResponse
    {
        $user = auth()->user();
        if (! $user->opd_id) {
            toast()->error('Oppss !!', 'User harus terhubung ke OPD untuk menambah kelompok masyarakat.');

            return back()->withInput();
        }

        try {
            DB::beginTransaction();
            $validated = $request->validated();
            $anggotaRows = array_values($validated['anggota'] ?? []);
            $dokumenRows = array_values($validated['dokumen'] ?? []);
            unset($validated['anggota'], $validated['dokumen'], $validated['jenis_pengajuan']);

            $organisasi = Organisasi::query()->create([
                ...$validated,
                'user_id' => $user->id,
                'opd_id' => $user->opd_id,
            ]);

            foreach ($anggotaRows as $row) {
                $penduduk = $this->resolveOrCreatePendudukForAnggota($row);
                OrganisasiDetail::query()->create([
                    'organisasi_id' => $organisasi->id,
                    'penduduk_id' => $penduduk->id,
                    'jabatan' => $row['jabatan'],
                ]);
            }

            foreach ($dokumenRows as $index => $row) {
                $dokumenModel = OrganisasiDokumen::query()->create([
                    'organisasi_id' => $organisasi->id,
                    'keterangan' => $row['keterangan'],
                    'jenis_dokumen' => $row['jenis_dokumen'],
                ]);
                $fileKey = "dokumen.{$index}.file";
                if ($request->hasFile($fileKey)) {
                    $dokumenModel->addMediaFromRequest($fileKey)->toMediaCollection('dokumen');
                }
            }

            DB::commit();
            toast()->success('Yeeayy !!', 'Data berhasil disimpan');

            return redirect()->route('kelompok-masyarakat.index');
        } catch (\Throwable $th) {
            DB::rollBack();
            toast()->error('Oppss !!', $th->getMessage());

            return back()->withInput();
        }
    }

    public function edit(string $kelompok_masyarakat)
    {

        $organisasi = Organisasi::query()
            ->with(['kecamatan.desa', 'organisasiDetail.penduduk', 'organisasiDokumen.media', 'jenisKelompokMasyarakat'])
            ->findOrFail($kelompok_masyarakat);

        $kecamatans = Kecamatan::query()->with('desa')->orderBy('nama')->get();

        $anggotaInitial = old('anggota');
        if ($anggotaInitial === null) {
            $anggotaInitial = $organisasi->organisasiDetail->map(function ($d) {
                $p = $d->penduduk;
                $jabatan = $d->jabatan instanceof JabatanOrganisasi
                    ? $d->jabatan->value
                    : $d->jabatan;
                if (! $p) {
                    return [
                        'organisasi_detail_id' => $d->id,
                        'nik' => '',
                        'nama' => '',
                        'jk' => '',
                        'kecamatan_id' => '',
                        'desa_id' => '',
                        'jabatan' => $jabatan,
                    ];
                }
                $jk = $p->jk instanceof JenisKelamin ? $p->jk->value : ($p->jk ?? '');

                return [
                    'organisasi_detail_id' => $d->id,
                    'nik' => $p->nik ?? '',
                    'nama' => $p->nama ?? '',
                    'jk' => $jk,
                    'kecamatan_id' => $p->kecamatan_id ?? '',
                    'desa_id' => $p->desa_id ?? '',
                    'jabatan' => $jabatan,
                    'is_valid' => (bool) $p->is_valid,
                    'validated_at' => $p->validated_at?->toIso8601String(),
                ];
            })->values()->all();
        }

        $dokumenInitial = old('dokumen');
        if ($dokumenInitial === null) {
            $dokumenInitial = $organisasi->organisasiDokumen->map(function ($d) {
                $media = $d->getFirstMedia('dokumen');

                return [
                    'id' => $d->id,
                    'jenis_dokumen' => $d->jenis_dokumen?->value ?? $d->jenis_dokumen,
                    'keterangan' => $d->keterangan,
                    'file_name' => $media?->file_name,
                    'file_url' => $media?->getUrl(),
                ];
            })->values()->all();
        }

        $jenisOrganisasiOptions = JenisOrganisasi::cases();
        $isBantuanKelompok = ! empty($organisasi->jenis_kelompok_masyarakat_id);
        $jenisPengajuanValue = $isBantuanKelompok ? JenisPengajuan::BANTUAN_KELOMPOK->value : null;
        $jenisKelompokMasyarakatOptions = $isBantuanKelompok
            ? JenisKelompokMasyarakat::query()->orderBy('nama')->get()
            : collect();

        return view('pages.kelompok-masyarakat.create', compact(
            'organisasi',
            'kecamatans',
            'anggotaInitial',
            'dokumenInitial',
            'jenisOrganisasiOptions',
            'jenisPengajuanValue',
            'isBantuanKelompok',
            'jenisKelompokMasyarakatOptions',
        ));
    }

    public function update(KelompokMasyarakatRequest $request, string $kelompok_masyarakat): ?\Illuminate\Http\RedirectResponse
    {
        $user = auth()->user();
        if (! $user->opd_id) {
            toast()->error('Oppss !!', 'User harus terhubung ke OPD untuk mengubah data.');

            return back()->withInput();
        }

        try {
            $organisasi = Organisasi::query()

                ->findOrFail($kelompok_masyarakat);

            DB::beginTransaction();
            $validated = $request->validated();
            $anggotaRows = array_values($validated['anggota'] ?? []);
            $dokumenRows = array_values($validated['dokumen'] ?? []);
            unset($validated['anggota'], $validated['dokumen'], $validated['jenis_pengajuan']);

            $organisasi->update([
                ...$validated,
                'opd_id' => $user->opd_id,
            ]);

            OrganisasiDetail::query()->where('organisasi_id', $organisasi->id)->delete();
            foreach ($anggotaRows as $row) {
                $penduduk = $this->resolveOrCreatePendudukForAnggota($row);
                OrganisasiDetail::query()->create([
                    'organisasi_id' => $organisasi->id,
                    'penduduk_id' => $penduduk->id,
                    'jabatan' => $row['jabatan'],
                ]);
            }

            $submittedDokumenIds = collect($dokumenRows)->pluck('id')->filter()->values()->all();
            $existingDokumen = OrganisasiDokumen::query()
                ->where('organisasi_id', $organisasi->id)
                ->get();
            foreach ($existingDokumen as $doc) {
                if (! in_array($doc->id, $submittedDokumenIds, true)) {
                    $doc->clearMediaCollection('dokumen');
                    $doc->delete();
                }
            }

            foreach ($dokumenRows as $index => $row) {
                $fileKey = "dokumen.{$index}.file";
                if (! empty($row['id'])) {
                    $dokumenModel = OrganisasiDokumen::query()
                        ->where('organisasi_id', $organisasi->id)
                        ->findOrFail($row['id']);
                    $dokumenModel->update([
                        'keterangan' => $row['keterangan'],
                        'jenis_dokumen' => $row['jenis_dokumen'],
                    ]);
                    if ($request->hasFile($fileKey)) {
                        $dokumenModel->clearMediaCollection('dokumen');
                        $dokumenModel->addMediaFromRequest($fileKey)->toMediaCollection('dokumen');
                    }
                } else {
                    $dokumenModel = OrganisasiDokumen::query()->create([
                        'organisasi_id' => $organisasi->id,
                        'keterangan' => $row['keterangan'],
                        'jenis_dokumen' => $row['jenis_dokumen'],
                    ]);
                    if ($request->hasFile($fileKey)) {
                        $dokumenModel->addMediaFromRequest($fileKey)->toMediaCollection('dokumen');
                    }
                }
            }

            DB::commit();
            toast()->success('Yeeayy !!', 'Data berhasil disimpan');

            return redirect()->route('kelompok-masyarakat.index');
        } catch (\Throwable $th) {
            DB::rollBack();
            toast()->error('Oppss !!', $th->getMessage());

            return back()->withInput();
        }
    }

    public function destroy(string $kelompok_masyarakat): ?\Illuminate\Http\RedirectResponse
    {
        try {
            $organisasi = Organisasi::query()
                ->findOrFail($kelompok_masyarakat);

            $organisasi->organisasiDetail()->delete();

            $dokumenList = $organisasi->organisasiDokumen()->get();
            foreach ($dokumenList as $dokumen) {
                $dokumen->clearMediaCollection('dokumen');
                $dokumen->delete();
            }
            $organisasi = Organisasi::query()

                ->findOrFail($kelompok_masyarakat);

            DB::beginTransaction();
            $organisasi->delete();
            DB::commit();
            toast()->success('Yeeayy !!', 'Data berhasil dihapus');

            return redirect()->route('kelompok-masyarakat.index');
        } catch (\Throwable $th) {
            toast()->error('Oppss !!', $th->getMessage());

            return redirect()->route('kelompok-masyarakat.index');
        }
    }
}
