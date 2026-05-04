<?php

namespace App\Exports;

use App\Exports\Sheets\AnggotaKelompokSheet;
use App\Exports\Sheets\KelompokSheet;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class LaporanKelompokExport implements WithMultipleSheets
{
    public function __construct(private Collection $kelompoks) {}

    public function sheets(): array
    {
        return [
            new KelompokSheet($this->kelompoks),
            new AnggotaKelompokSheet($this->kelompoks),
        ];
    }
}
