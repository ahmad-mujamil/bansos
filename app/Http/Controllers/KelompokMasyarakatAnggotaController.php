<?php

namespace App\Http\Controllers;

use App\Enums\JenisOrganisasi;
use App\Http\Requests\KelompokMasyarakatAnggotaRequest;
use App\Models\Organisasi;
use App\Models\OrganisasiDetail;
use App\Models\Penduduk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class KelompokMasyarakatAnggotaController extends Controller
{
    public function index(string $kelompok_masyarakat)
    {
        $organisasi = Organisasi::query()
            ->where('jenis', JenisOrganisasi::KELOMPOK)
            ->findOrFail($kelompok_masyarakat);

        confirmDelete("Hapus Anggota", "Apakah Anda yakin ingin menghapus anggota ini?");
        if (request()->ajax()) {
            return $this->data($kelompok_masyarakat);
        }

        $penduduks = Penduduk::query()->orderBy('nama')->get();
        return view('pages.kelompok-masyarakat.anggota.index', compact('organisasi', 'penduduks'));
    }

    private function data(string $organisasiId): \Illuminate\Http\JsonResponse
    {
        $data = OrganisasiDetail::query()
            ->with('penduduk')
            ->where('organisasi_id', $organisasiId)
            ->latest();

        return DataTables::of($data)
            ->addColumn('nama_penduduk', fn($row) => $row->penduduk->nama ?? '-')
            ->addColumn('nik_penduduk', fn($row) => $row->penduduk->nik ?? '-')
            ->addColumn('jabatan_label', fn($row) => $row->jabatan?->getDescription() ?? $row->jabatan)
            ->addColumn('action', function ($row) use ($organisasiId) {
                $navActionStart = '<nav class="breadcrumb-container d-inline-block" aria-label="breadcrumb"><ul class="breadcrumb pt-0">';
                $navActionEnd = "</ul></nav>";

                $delete = "<li class='breadcrumb-item'><a href='" . route('kelompok-masyarakat.anggota.destroy', [$organisasiId, $row->id]) . "' data-confirm-delete='true'
                        title='Hapus' class='fw-bold text-danger'>Delete</a></li>";

                $jabatanVal = $row->jabatan ? (is_object($row->jabatan) ? $row->jabatan->value : $row->jabatan) : '';
                $edit = "<li class='breadcrumb-item'><a href='#' class='btn-edit-anggota fw-bold text-success' title='Edit'
                        data-anggota-id='" . $row->id . "' data-penduduk-id='" . $row->penduduk_id . "' data-jabatan='" . e($jabatanVal) . "'>Edit</a></li>";

                return $navActionStart . $edit . $delete . $navActionEnd;
            })
            ->rawColumns(['action'])
            ->toJson();
    }

    public function create(string $kelompok_masyarakat)
    {
        $organisasi = Organisasi::query()
            ->where('jenis', JenisOrganisasi::KELOMPOK)
            ->findOrFail($kelompok_masyarakat);

        $penduduks = Penduduk::query()->orderBy('nama')->get();
        $anggota = null;
        return view('pages.kelompok-masyarakat.anggota.create', compact('organisasi', 'penduduks', 'anggota'));
    }

    public function store(KelompokMasyarakatAnggotaRequest $request, string $kelompok_masyarakat): \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
    {
        $organisasi = Organisasi::query()
            ->where('jenis', JenisOrganisasi::KELOMPOK)
            ->findOrFail($kelompok_masyarakat);

        try {
            DB::beginTransaction();
            OrganisasiDetail::query()->create([
                ...$request->validated(),
                'organisasi_id' => $organisasi->id,
            ]);
            DB::commit();

            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['success' => true, 'message' => 'Anggota berhasil ditambahkan']);
            }
            toast()->success('Yeeayy !!', 'Anggota berhasil ditambahkan');
            return redirect()->route('kelompok-masyarakat.anggota.index', $kelompok_masyarakat);
        } catch (\Throwable $th) {
            DB::rollBack();
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => $th->getMessage()], 500);
            }
            toast()->error('Oppss !!', $th->getMessage());
            return back()->withInput();
        }
    }

    public function edit(string $kelompok_masyarakat, string $anggota)
    {
        $organisasi = Organisasi::query()
            ->where('jenis', JenisOrganisasi::KELOMPOK)
            ->findOrFail($kelompok_masyarakat);

        $detail = OrganisasiDetail::query()
            ->where('organisasi_id', $kelompok_masyarakat)
            ->findOrFail($anggota);

        $penduduks = Penduduk::query()->orderBy('nama')->get();
        return view('pages.kelompok-masyarakat.anggota.create', [
            'organisasi' => $organisasi,
            'penduduks' => $penduduks,
            'anggota' => $detail,
        ]);
    }

    public function update(KelompokMasyarakatAnggotaRequest $request, string $kelompok_masyarakat, string $anggota): \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
    {
        $detail = OrganisasiDetail::query()
            ->where('organisasi_id', $kelompok_masyarakat)
            ->findOrFail($anggota);

        try {
            DB::beginTransaction();
            $detail->update($request->validated());
            DB::commit();

            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['success' => true, 'message' => 'Data anggota berhasil disimpan']);
            }
            toast()->success('Yeeayy !!', 'Data anggota berhasil disimpan');
            return redirect()->route('kelompok-masyarakat.anggota.index', $kelompok_masyarakat);
        } catch (\Throwable $th) {
            DB::rollBack();
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => $th->getMessage()], 500);
            }
            toast()->error('Oppss !!', $th->getMessage());
            return back()->withInput();
        }
    }

    public function destroy(string $kelompok_masyarakat, string $anggota): \Illuminate\Http\RedirectResponse
    {
        $detail = OrganisasiDetail::query()
            ->where('organisasi_id', $kelompok_masyarakat)
            ->findOrFail($anggota);

        try {
            DB::beginTransaction();
            $detail->delete();
            DB::commit();
            toast()->success('Yeeayy !!', 'Anggota berhasil dihapus');
            return redirect()->route('kelompok-masyarakat.anggota.index', $kelompok_masyarakat);
        } catch (\Throwable $th) {
            toast()->error('Oppss !!', $th->getMessage());
            return redirect()->route('kelompok-masyarakat.anggota.index', $kelompok_masyarakat);
        }
    }
}
