<?php

namespace App\Http\Controllers;

use App\Enums\JenisOrganisasi;
use App\Models\Penduduk;
use Yajra\DataTables\Facades\DataTables;

class LaporanAnggotaKelompokController extends Controller
{
    private function baseQuery()
    {
        return Penduduk::query()
            ->with([
                'desa',
                'kecamatan',
                'organisasiDetails' => function ($q) {
                    $q->whereHas('organisasi', fn ($o) => $o->where('jenis', JenisOrganisasi::KELOMPOK))
                        ->with('organisasi');
                },
            ])
            ->whereHas('organisasiDetails', function ($q) {
                $q->whereHas('organisasi', fn ($o) => $o->where('jenis', JenisOrganisasi::KELOMPOK));
            })
            ->withCount([
                'organisasiDetails as jumlah_kelompok' => function ($query) {
                    $query->whereHas('organisasi', fn ($o) => $o->where('jenis', JenisOrganisasi::KELOMPOK));
                },
            ]);
    }

    private function daftarNamaKelompokCsv(Penduduk $p): string
    {
        $names = $p->organisasiDetails
            ->map(fn ($d) => $d->organisasi?->nama)
            ->filter()
            ->unique()
            ->sort()
            ->values();

        return $names->isEmpty()
            ? ''
            : $names->implode(', ');
    }

    private function data()
    {
        $query = $this->baseQuery();

        return DataTables::of($query)
            ->addColumn('nama_penduduk', fn (Penduduk $p) => e($p->nama))
            ->addColumn('nik', fn (Penduduk $p) => e($p->nik))
            ->addColumn('wilayah', function (Penduduk $p) {
                $parts = array_filter([
                    $p->desa?->nama,
                    $p->kecamatan?->nama,
                ]);

                return $parts !== []
                    ? e(implode(', ', $parts))
                    : '<span class="text-muted">—</span>';
            })
            ->addColumn('daftar_kelompok', function (Penduduk $p) {
                $csv = $this->daftarNamaKelompokCsv($p);

                return $csv !== ''
                    ? e($csv)
                    : '<span class="text-muted">—</span>';
            })
            ->filterColumn('daftar_kelompok', function ($query, $keyword) {
                $query->whereHas('organisasiDetails', function ($q) use ($keyword) {
                    $q->whereHas('organisasi', function ($o) use ($keyword) {
                        $o->where('jenis', JenisOrganisasi::KELOMPOK)
                            ->where('nama', 'like', '%'.$keyword.'%');
                    });
                });
            })
            ->addColumn('jumlah_kelompok', fn (Penduduk $p) => '<span class="fw-semibold">'.(int) $p->jumlah_kelompok.'</span>')
            ->orderColumn('jumlah_kelompok', 'jumlah_kelompok $1')
            ->rawColumns(['wilayah', 'daftar_kelompok', 'jumlah_kelompok'])
            ->toJson();
    }

    public function index()
    {
        if (request()->ajax()) {
            return $this->data();
        }

        return view('pages.laporan-anggota-kelompok.index');
    }
}
