<?php

namespace App\Exports\Sheets;

use App\Models\OrganisasiDetail;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class AnggotaKelompokSheet implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles, WithTitle, WithColumnFormatting, WithEvents
{
    public function __construct(private Collection $kelompoks) {}

    public function title(): string
    {
        return 'Anggota Kelompok';
    }

    public function headings(): array
    {
        return [
            'No', 'Nama Kelompok', 'OPD', 'NIK', 'Nama Anggota',
            'Jabatan', 'Status Verifikasi', 'Catatan Verifikasi',
        ];
    }

    public function collection(): Collection
    {
        $this->kelompoks->loadMissing(['organisasiDetail.penduduk']);

        $rows = collect();
        $no = 1;

        foreach ($this->kelompoks as $kelompok) {
            $details = $kelompok->organisasiDetail
                ->sortBy(fn (OrganisasiDetail $d) => array_search(
                    $d->jabatan?->value,
                    ['Ketua', 'Wakil Ketua', 'Sekretaris', 'Bendahara', 'Admin', 'Anggota'],
                    true
                ));

            foreach ($details as $detail) {
                $rows->push([
                    $no++,
                    $kelompok->nama,
                    $kelompok->opd?->nama ?? '-',
                    (string) ($detail->penduduk?->nik ?? '-'),
                    $detail->penduduk?->nama ?? '-',
                    $detail->jabatan?->value ?? '-',
                    $detail->penduduk?->labelStatusVerifikasi() ?? '-',
                    $detail->penduduk?->catatan_validasi ?? '-',
                ]);
            }
        }

        return $rows;
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }

    public function columnFormats(): array
    {
        return [
            'D' => NumberFormat::FORMAT_TEXT,
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $highestRow = $sheet->getHighestRow();

                for ($row = 2; $row <= $highestRow; $row++) {
                    $cell = $sheet->getCell("D{$row}");
                    $cell->setValueExplicit((string) $cell->getValue(), DataType::TYPE_STRING);
                }
            },
        ];
    }
}
