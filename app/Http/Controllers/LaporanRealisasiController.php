<?php

namespace App\Http\Controllers;

use App\Enums\JenisPengajuan;
use App\Enums\RoleUser;
use App\Models\Opd;
use App\Models\Pengajuan;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class LaporanRealisasiController extends Controller
{
    private function pengajuanQuery()
    {
        $q = Pengajuan::query()
            ->whereHas('bast')
            ->with(['bast', 'realisasi', 'verifikasiPengajuan', 'opd']);

        $user = Auth::user();

        if ($user->is_opd()) {
            $q->where('opd_id', $user->opd_id);
        }

        $this->applyFilters($q);

        return $q->latest();
    }

    /**
     * Filter laporan realisasi — mengikuti laporan pengajuan (jenis bantuan, OPD,
     * bulan) ditambah status realisasi yang khas halaman ini.
     */
    private function applyFilters(Builder $query): void
    {
        $kategori = (string) request('kategori', 'all');
        if ($kategori !== 'all' && JenisPengajuan::tryFrom($kategori) !== null) {
            $query->where(function (Builder $q) use ($kategori) {
                $q->where('kategori_pengajuan', $kategori)
                    ->orWhere(function (Builder $q2) use ($kategori) {
                        $q2->whereNull('kategori_pengajuan')
                            ->whereHas('jenisBantuan', fn (Builder $jb) => $jb->where('kategori', $kategori));
                    });
            });
        }

        $opd = (string) request('opd', 'all');
        if ($this->showOpdFilter() && $opd !== 'all' && $opd !== '') {
            $query->where('opd_id', $opd);
        }

        // Periode bulan; tahun mengikuti Tahun Anggaran terpilih (global scope tahun_anggaran).
        $bulan = (int) request('bulan', 0);
        if (in_array($bulan, range(1, 12), true)) {
            $query->whereMonth('pengajuan.created_at', $bulan);
        }

        $realisasi = (string) request('realisasi', 'all');
        if ($realisasi === 'sudah') {
            $query->whereHas('realisasi');
        } elseif ($realisasi === 'belum') {
            $query->whereDoesntHave('realisasi');
        }
    }

    /**
     * Filter OPD hanya untuk role yang melihat lintas OPD.
     */
    private function showOpdFilter(): bool
    {
        $user = Auth::user();

        return $user?->role === RoleUser::SUPER || $user?->role === RoleUser::ADMIN;
    }

    private function data()
    {
        return DataTables::of($this->pengajuanQuery())
            ->addColumn('opd', fn ($row) => $row->opd?->nama ?? '-')
            ->addColumn('bast_tanggal', fn ($row) => $row->bast?->tanggal?->format('d-m-Y') ?? '-')
            ->addColumn('nilai_pengajuan', fn ($row) => 'Rp '.number_format((float) $row->nilai, 0, ',', '.'))
            ->addColumn('nilai_rekomendasi', function ($row) {
                $nilai = $row->verifikasiPengajuan?->nilai_rekomendasi;

                return $nilai !== null ? 'Rp '.number_format($nilai, 0, ',', '.') : '-';
            })
            ->addColumn('status_realisasi', function ($row) {
                return $row->realisasi
                    ? '<span class="badge bg-success">Sudah</span>'
                    : '<span class="badge bg-warning">Belum</span>';
            })
            ->rawColumns(['status_realisasi'])
            ->toJson();
    }

    public function index()
    {
        if (request()->ajax()) {
            return $this->data();
        }

        return view('pages.laporan-realisasi.index', [
            'showOpdFilter' => $this->showOpdFilter(),
            'opds' => $this->showOpdFilter()
                ? Opd::query()->orderBy('nama')->get(['id', 'nama'])
                : collect(),
        ]);
    }
}
