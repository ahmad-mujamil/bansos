<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class LaporanKelompokExport implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles
{
    public function __construct(private Collection $kelompoks) {}

    public function headings(): array
    {
        return [
            'No', 'Nama Kelompok', 'Nomor SK', 'Tanggal Pembentukan',
            'Jenis Kelompok', 'OPD', 'Kecamatan', 'Desa/Kelurahan',
            'Jumlah Anggota', 'Status',
        ];
    }

    public function collection(): Collection
    {
        return $this->kelompoks->values()->map(fn ($row, $i) => [
            $i + 1,
            $row->nama,
            $row->nomor ?? '-',
            $row->tgl_pembentukan?->format('d/m/Y') ?? '-',
            $row->jenisKelompokMasyarakat?->nama ?? '-',
            $row->opd?->nama ?? '-',
            $row->kecamatan?->nama ?? '-',
            $row->desa?->nama ?? '-',
            $row->organisasi_detail_count,
            $row->is_active ? 'Aktif' : 'Nonaktif',
        ]);
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}