<?php

namespace App\Exports\Sheets;

use App\Enums\JenisOrganisasi;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class KelompokSheet implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles, WithTitle
{
    public function __construct(private Collection $kelompoks) {}

    public function title(): string
    {
        return 'Daftar Kelompok';
    }

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
            JenisOrganisasi::tryFrom((string) $row->jenis)?->getDescription() ?? '-',
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
