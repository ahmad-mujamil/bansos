<?php

namespace App\Http\Controllers;

use App\Exports\LaporanPenerimaExport;
use App\Models\Penduduk;
use App\Models\Pengajuan;
use Illuminate\Http\JsonResponse;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class LaporanPenerimaController extends Controller
{
    private function applyPengajuanFilters($q): void
    {
        $bulan        = request('bulan');
        $tahun        = request('tahun');
        $jenisPenerima = request('jenis_penerima');

        if ($bulan)        $q->whereMonth('created_at', $bulan);
        if ($tahun)        $q->whereYear('created_at', $tahun);
        if ($jenisPenerima) $q->where('jenis_penerima_bantuan', $jenisPenerima);
    }

    private function baseQuery()
    {
        return Penduduk::query()
            ->with(['desa', 'kecamatan'])
            ->where(function ($q) {
                $q->whereHas('pengajuanDetails.pengajuan', fn($pq) => $this->applyPengajuanFilters($pq))
                  ->orWhereHas('organisasiDetails.organisasi.pengajuans', fn($pq) => $this->applyPengajuanFilters($pq));
            })
            ->orderBy('nama');
    }

    private function data()
    {
        return DataTables::of($this->baseQuery())
            ->addColumn('jk_label', fn($row) => $row->jk?->getDescription() ?? '-')
            ->addColumn('desa_nama', fn($row) => $row->desa?->nama ?? '-')
            ->addColumn('kecamatan_nama', fn($row) => $row->kecamatan?->nama ?? '-')
            ->toJson();
    }

    public function index()
    {
        if (request()->ajax()) {
            return $this->data();
        }

        return view('pages.laporan-penerima.index');
    }

    public function penerimaan(Penduduk $penduduk): JsonResponse
    {
        $rows = collect();

        // Path 1: penerimaan individu/keluarga via PengajuanDetail
        $details = $penduduk->pengajuanDetails()
            ->with(['pengajuan.jenisBantuan', 'pengajuan.bast'])
            ->whereHas('pengajuan', fn($q) => $this->applyPengajuanFilters($q))
            ->get()
            ->map(fn($d) => [
                'kode_pengajuan'  => $d->pengajuan->kode_pengajuan,
                'jenis_bantuan'   => $d->pengajuan->jenisBantuan?->nama ?? '-',
                'nilai'           => (float) ($d->nilai ?? $d->pengajuan->nilai),
                'cara_penerimaan' => $d->pengajuan->jenis_penerima_bantuan?->getDescription() ?? '-',
                'nama_kelompok'   => '-',
                'tanggal'         => $d->pengajuan->bast?->tanggal?->format('d-m-Y')
                                      ?? $d->pengajuan->created_at?->format('d-m-Y') ?? '-',
                'status'          => $d->pengajuan->status?->getDescription() ?? '-',
                'badge'           => $d->pengajuan->status?->badgeColor() ?? 'secondary',
            ]);

        $rows = $rows->merge($details);

        // Path 2: penerimaan via keanggotaan organisasi/kelompok
        $kelompokPengajuans = Pengajuan::query()
            ->whereHas('organisasi.organisasiDetail', fn($q) => $q->where('penduduk_id', $penduduk->id))
            ->with(['jenisBantuan', 'bast', 'organisasi'])
            ->where(fn($q) => $this->applyPengajuanFilters($q))
            ->get()
            ->map(fn($p) => [
                'kode_pengajuan'  => $p->kode_pengajuan,
                'jenis_bantuan'   => $p->jenisBantuan?->nama ?? '-',
                'nilai'           => (float) $p->nilai,
                'cara_penerimaan' => $p->jenis_penerima_bantuan?->getDescription() ?? 'Kelompok/Organisasi',
                'nama_kelompok'   => $p->organisasi?->nama ?? '-',
                'tanggal'         => $p->bast?->tanggal?->format('d-m-Y')
                                      ?? $p->created_at?->format('d-m-Y') ?? '-',
                'status'          => $p->status?->getDescription() ?? '-',
                'badge'           => $p->status?->badgeColor() ?? 'secondary',
            ]);

        return response()->json($rows->merge($kelompokPengajuans)->values());
    }

    public function export()
    {
        $filename = 'laporan-penerima-' . date('YmdHis') . '.xlsx';

        return Excel::download(
            new LaporanPenerimaExport(
                bulan:        request('bulan'),
                tahun:        request('tahun'),
                jenisPenerima: request('jenis_penerima'),
            ),
            $filename
        );
    }
}
