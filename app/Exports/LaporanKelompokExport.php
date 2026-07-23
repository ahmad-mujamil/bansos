<?php

namespace App\Exports;

use App\Enums\JenisOrganisasi;
use App\Models\OrganisasiDetail;
use App\Models\Penduduk;
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

class LaporanKelompokExport implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles, WithTitle, WithColumnFormatting, WithEvents
{
    public function __construct(private Collection $kelompoks) {}

    public function title(): string
    {
        return 'Laporan Kelompok';
    }

    public function headings(): array
    {
        return [
            'No',
            'Nama Kelompok',
            'Nomor SK',
            'Tanggal Pembentukan',
            'Jenis',
            'Jenis Kelompok Masyarakat',
            'OPD',
            'Kecamatan',
            'Desa/Kelurahan',
            'Jumlah Anggota',
            'Status',
            'Tahun Anggaran',
            'NIK',
            'Nama Anggota',
            'Jabatan',
            'Desil',
            'Status Verifikasi',
            'Catatan Verifikasi',
        ];
    }

    public function collection(): Collection
    {
        $this->kelompoks->loadMissing(['organisasiDetail.penduduk']);

        $rows = collect();
        $no = 0;

        foreach ($this->kelompoks as $kelompok) {
            $kelompokCols = [
                $kelompok->nama,
                $kelompok->nomor ?? '-',
                $kelompok->tgl_pembentukan?->format('d/m/Y') ?? '-',
                JenisOrganisasi::tryFrom((string) $kelompok->jenis)?->getDescription() ?? '-',
                $kelompok->jenisKelompokMasyarakat?->nama ?? '-',
                $kelompok->opd?->nama ?? '-',
                $kelompok->kecamatan?->nama ?? '-',
                $kelompok->desa?->nama ?? '-',
                $kelompok->organisasi_detail_count ?? $kelompok->organisasiDetail->count(),
                $kelompok->is_active ? 'Aktif' : 'Nonaktif',
                $kelompok->tahun_anggaran ?? '-',
            ];

            $details = $kelompok->organisasiDetail
                ->sortBy(fn (OrganisasiDetail $d) => array_search(
                    $d->jabatan?->value,
                    ['Ketua', 'Wakil Ketua', 'Sekretaris', 'Bendahara', 'Admin', 'Anggota'],
                    true
                ));

            if ($details->isEmpty()) {
                $rows->push(array_merge([++$no], $kelompokCols, ['-', '-', '-', '-', '-', '-']));

                continue;
            }

            foreach ($details as $detail) {
                $penduduk = $detail->penduduk;

                $rows->push(array_merge([++$no], $kelompokCols, [
                    (string) ($penduduk?->nik ?? '-'),
                    $penduduk?->nama ?? '-',
                    $detail->jabatan?->value ?? '-',
                    $this->desilLabel($penduduk),
                    $penduduk?->labelStatusVerifikasi() ?? '-',
                    $penduduk?->catatan_validasi ?? '-',
                ]));
            }
        }

        return $rows;
    }

    private function desilLabel(?Penduduk $penduduk): string
    {
        $desil = $penduduk?->level_desil;

        return $desil
            ? $desil->value . ' - ' . $desil->getDescription()
            : '-';
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
            'M' => NumberFormat::FORMAT_TEXT,
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $highestRow = $sheet->getHighestRow();

                for ($row = 2; $row <= $highestRow; $row++) {
                    $cell = $sheet->getCell("M{$row}");
                    $cell->setValueExplicit((string) $cell->getValue(), DataType::TYPE_STRING);
                }
            },
        ];
    }
}
