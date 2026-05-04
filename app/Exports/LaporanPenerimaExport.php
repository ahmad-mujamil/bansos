<?php

namespace App\Exports;

use App\Models\PengajuanDetail;
use App\Models\Pengajuan;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class LaporanPenerimaExport implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles
{
    public function __construct(
        private ?string $bulan,
        private ?string $tahun,
        private ?string $jenisPenerima,
    ) {}

    public function headings(): array
    {
        return [
            'No', 'NIK', 'Nama', 'Alamat', 'Jenis Kelamin', 'Desa/Kelurahan', 'Kecamatan',
            'Kode Pengajuan', 'Jenis Bantuan', 'Nilai Bantuan', 'Cara Penerimaan', 'Nama Kelompok',
            'Tanggal', 'Status',
        ];
    }

    public function collection(): Collection
    {
        $rows = collect();

        // Path 1: individual/family via PengajuanDetail
        PengajuanDetail::query()
            ->with(['penduduk.desa', 'penduduk.kecamatan', 'pengajuan.jenisBantuan', 'pengajuan.bast'])
            ->whereHas('pengajuan', function ($q) {
                if ($this->bulan)        $q->whereMonth('created_at', $this->bulan);
                if ($this->tahun)        $q->whereYear('created_at', $this->tahun);
                if ($this->jenisPenerima) $q->where('jenis_penerima_bantuan', $this->jenisPenerima);
            })
            ->whereHas('penduduk')
            ->get()
            ->each(function ($d) use ($rows) {
                $p = $d->penduduk;
                $rows->push([
                    $p?->nik ?? '-',
                    $p?->nama ?? '-',
                    $p?->alamat ?? '-',
                    $p?->jk?->getDescription() ?? '-',
                    $p?->desa?->nama ?? '-',
                    $p?->kecamatan?->nama ?? '-',
                    $d->pengajuan->kode_pengajuan,
                    $d->pengajuan->jenisBantuan?->nama ?? '-',
                    (float) ($d->nilai ?? $d->pengajuan->nilai),
                    $d->pengajuan->jenis_penerima_bantuan?->getDescription() ?? '-',
                    '-',
                    $d->pengajuan->bast?->tanggal?->format('d/m/Y') ?? $d->pengajuan->created_at?->format('d/m/Y') ?? '-',
                    $d->pengajuan->status?->getDescription() ?? '-',
                ]);
            });

        // Path 2: group aid via Organisasi members
        Pengajuan::query()
            ->with(['jenisBantuan', 'bast', 'organisasi.organisasiDetail.penduduk.desa', 'organisasi.organisasiDetail.penduduk.kecamatan'])
            ->whereNotNull('organisasi_id')
            ->when($this->bulan,        fn($q) => $q->whereMonth('created_at', $this->bulan))
            ->when($this->tahun,        fn($q) => $q->whereYear('created_at', $this->tahun))
            ->when($this->jenisPenerima, fn($q) => $q->where('jenis_penerima_bantuan', $this->jenisPenerima))
            ->get()
            ->each(function ($pengajuan) use ($rows) {
                foreach ($pengajuan->organisasi?->organisasiDetail ?? [] as $detail) {
                    $p = $detail->penduduk;
                    if (! $p) continue;
                    $rows->push([
                        $p->nik ?? '-',
                        $p->nama ?? '-',
                        $p->alamat ?? '-',
                        $p->jk?->getDescription() ?? '-',
                        $p->desa?->nama ?? '-',
                        $p->kecamatan?->nama ?? '-',
                        $pengajuan->kode_pengajuan,
                        $pengajuan->jenisBantuan?->nama ?? '-',
                        (float) $pengajuan->nilai,
                        $pengajuan->jenis_penerima_bantuan?->getDescription() ?? 'Kelompok/Organisasi',
                        $pengajuan->organisasi?->nama ?? '-',
                        $pengajuan->bast?->tanggal?->format('d/m/Y') ?? $pengajuan->created_at?->format('d/m/Y') ?? '-',
                        $pengajuan->status?->getDescription() ?? '-',
                    ]);
                }
            });

        return $rows->values()->map(fn($row, $i) => array_merge([$i + 1], $row));
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
