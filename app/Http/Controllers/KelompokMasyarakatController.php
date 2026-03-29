<?php

namespace App\Http\Controllers;

use App\Enums\JabatanOrganisasi;
use App\Enums\JenisOrganisasi;
use App\Http\Requests\KelompokMasyarakatRequest;
use App\Models\Kecamatan;
use App\Models\Organisasi;
use App\Models\OrganisasiDetail;
use App\Models\OrganisasiDokumen;
use App\Models\Penduduk;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class KelompokMasyarakatController extends Controller
{
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

    public function create()
    {
        $organisasi = null;
        $kecamatans = Kecamatan::query()->with('desa')->orderBy('nama')->get();
        $penduduks = Penduduk::query()->orderBy('nama')->get();
        $anggotaInitial = old('anggota');
        if ($anggotaInitial === null) {
            $anggotaInitial = [];
        }
        $dokumenInitial = old('dokumen');
        if ($dokumenInitial === null) {
            $dokumenInitial = [];
        }

        return view('pages.kelompok-masyarakat.create', compact('organisasi', 'kecamatans', 'penduduks', 'anggotaInitial', 'dokumenInitial'));
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
            unset($validated['anggota'], $validated['dokumen']);

            $organisasi = Organisasi::query()->create([
                ...$validated,
                'user_id' => $user->id,
                'opd_id' => $user->opd_id,
            ]);

            foreach ($anggotaRows as $row) {
                OrganisasiDetail::query()->create([
                    'organisasi_id' => $organisasi->id,
                    'penduduk_id' => $row['penduduk_id'],
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
            ->with(['kecamatan.desa', 'organisasiDetail', 'organisasiDokumen.media'])
            ->where('jenis', JenisOrganisasi::KELOMPOK)
            ->findOrFail($kelompok_masyarakat);

        $kecamatans = Kecamatan::query()->with('desa')->orderBy('nama')->get();
        $penduduks = Penduduk::query()->orderBy('nama')->get();

        $anggotaInitial = old('anggota');
        if ($anggotaInitial === null) {
            $anggotaInitial = $organisasi->organisasiDetail->map(function ($d) {
                return [
                    'penduduk_id' => $d->penduduk_id,
                    'jabatan' => $d->jabatan instanceof JabatanOrganisasi
                        ? $d->jabatan->value
                        : $d->jabatan,
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

        return view('pages.kelompok-masyarakat.create', compact('organisasi', 'kecamatans', 'penduduks', 'anggotaInitial', 'dokumenInitial'));
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
                ->where('jenis', JenisOrganisasi::KELOMPOK)
                ->findOrFail($kelompok_masyarakat);

            DB::beginTransaction();
            $validated = $request->validated();
            $anggotaRows = array_values($validated['anggota'] ?? []);
            $dokumenRows = array_values($validated['dokumen'] ?? []);
            unset($validated['anggota'], $validated['dokumen']);

            $organisasi->update([
                ...$validated,
                'opd_id' => $user->opd_id,
            ]);

            OrganisasiDetail::query()->where('organisasi_id', $organisasi->id)->delete();
            foreach ($anggotaRows as $row) {
                OrganisasiDetail::query()->create([
                    'organisasi_id' => $organisasi->id,
                    'penduduk_id' => $row['penduduk_id'],
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
                ->where('jenis', JenisOrganisasi::KELOMPOK)
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
