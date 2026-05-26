<?php

namespace App\Exports\Sheets;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PendudukTemplateSheet implements FromArray, WithHeadings, WithStyles, ShouldAutoSize, WithEvents, WithTitle
{
    private const MAX_DATA_ROWS = 500;

    public function __construct(private Collection $desas) {}

    public function title(): string
    {
        return 'Data Penduduk';
    }

    public function headings(): array
    {
        return [
            'nik',
            'no_kk',
            'nama',
            'alamat',
            'tempat_lahir',
            'tgl_lahir',
            'jk',
            'agama',
            'status_perkawinan',
            'pekerjaan',
            'pendidikan',
            'rt_rw',
            'desa',
            'level_desil',
        ];
    }

    public function array(): array
    {
        return [
            [
                '3201010101010001',
                '3201010101010001',
                'Contoh Nama',
                'Jl. Contoh No. 1',
                'Bandung',
                '1990-01-15',
                'L',
                'Islam',
                'Kawin',
                'Petani',
                'SMA',
                '001/002',
                $this->desas->first() ?? 'Nama Desa',
                '3',
            ],
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $lastRow = self::MAX_DATA_ROWS + 1;

                // Force NIK (A) and no_kk (B) to text format for the entire column
                $sheet->getStyle('A:A')->getNumberFormat()->setFormatCode('@');
                $sheet->getStyle('B:B')->getNumberFormat()->setFormatCode('@');
                $sheet->getStyle("A2:A{$lastRow}")->getNumberFormat()->setFormatCode('@');
                $sheet->getStyle("B2:B{$lastRow}")->getNumberFormat()->setFormatCode('@');

                // Re-write the example NIK & no_kk cells explicitly as strings,
                // because FromArray writes them as numbers before format is applied.
                $sheet->setCellValueExplicit('A2', '3201010101010001', DataType::TYPE_STRING);
                $sheet->setCellValueExplicit('B2', '3201010101010001', DataType::TYPE_STRING);

                // jk dropdown (column G)
                $this->applyListValidation($sheet, "G2:G{$lastRow}", '"L,P"');

                // status_perkawinan dropdown (column I)
                $this->applyListValidation($sheet, "I2:I{$lastRow}", '"Belum Kawin,Kawin,Cerai Hidup,Cerai Mati"');

                // level_desil dropdown (column N)
                $this->applyListValidation($sheet, "N2:N{$lastRow}", '"1,2,3,4,5,6,7,8,9,10"');

                // desa dropdown (column M) pulling from Referensi sheet column A
                if ($this->desas->isNotEmpty()) {
                    $desaLastRow = $this->desas->count() + 1;
                    $this->applyListValidation($sheet, "M2:M{$lastRow}", "=Referensi!\$A\$2:\$A\${$desaLastRow}");
                }

                // Petunjuk pengisian — letakkan di sebelah kanan area data agar tetap terlihat
                $notes = [
                    'PETUNJUK PENGISIAN',
                    '1. nik & no_kk sudah diformat teks; ketik langsung angkanya.',
                    '2. tgl_lahir: format YYYY-MM-DD (contoh: 1990-01-15).',
                    '3. jk: pilih L atau P dari dropdown.',
                    '4. status_perkawinan: pilih dari dropdown (Belum Kawin / Kawin / Cerai Hidup / Cerai Mati).',
                    '5. level_desil: pilih angka 1-10 dari dropdown.',
                    '6. desa: pilih dari dropdown — kecamatan otomatis terisi saat import berdasarkan desa.',
                    '7. Hapus baris contoh (baris 2) sebelum melakukan import.',
                    '8. NIK harus unik — duplikat dalam file atau yang sudah ada di database akan dilewati.',
                ];

                foreach ($notes as $i => $line) {
                    $sheet->setCellValue('Q' . ($i + 1), $line);
                }
                $sheet->getStyle('Q1')->getFont()->setBold(true)->setSize(12);
                $sheet->getStyle('Q1:Q' . count($notes))
                    ->getAlignment()->setWrapText(true);
                $sheet->getColumnDimension('Q')->setWidth(70);
                $sheet->getStyle('Q1:Q' . count($notes))
                    ->getFill()
                    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    ->getStartColor()->setRGB('FFF8E1');
            },
        ];
    }

    private function applyListValidation(Worksheet $sheet, string $range, string $formula1): void
    {
        $validation = $sheet->getCell(explode(':', $range)[0])->getDataValidation();
        $validation->setType(DataValidation::TYPE_LIST);
        $validation->setErrorStyle(DataValidation::STYLE_STOP);
        $validation->setAllowBlank(true);
        $validation->setShowInputMessage(true);
        $validation->setShowErrorMessage(true);
        $validation->setShowDropDown(true);
        $validation->setErrorTitle('Input tidak valid');
        $validation->setError('Pilih nilai dari daftar yang tersedia.');
        $validation->setFormula1($formula1);
        $sheet->setDataValidation($range, $validation);
    }
}
