<?php

namespace App\Exports\Sheets;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ReferensiWilayahSheet implements FromArray, WithHeadings, WithStyles, ShouldAutoSize, WithTitle
{
    /**
     * @param Collection<int, array{nama:string,kecamatan:string}> $desas
     */
    public function __construct(private Collection $desas) {}

    public function title(): string
    {
        return 'Referensi';
    }

    public function headings(): array
    {
        return ['Desa', 'Kecamatan'];
    }

    public function array(): array
    {
        return $this->desas
            ->map(fn ($d) => [$d['nama'], $d['kecamatan']])
            ->all();
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
