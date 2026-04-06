<?php

namespace App\Http\Controllers;

use App\Models\Opd;
use App\Models\Pengajuan;
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
        } else {
            $opdId = request('opd_id');
            if (is_string($opdId) && $opdId !== '' && Opd::query()->whereKey($opdId)->exists()) {
                $q->where('opd_id', $opdId);
            }
        }

        return $q->latest();
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

        $opds = Opd::query()->orderBy('nama')->get(['id', 'nama']);

        return view('pages.laporan-realisasi.index', compact('opds'));
    }
}
