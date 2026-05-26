<?php

namespace App\Exports;

use App\Exports\Sheets\PendudukTemplateSheet;
use App\Exports\Sheets\ReferensiWilayahSheet;
use App\Models\Desa;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class PendudukTemplateExport implements WithMultipleSheets
{
    public function sheets(): array
    {
        $desas = Desa::query()
            ->with('kecamatan')
            ->orderBy('nama')
            ->get()
            ->map(fn ($d) => [
                'nama'      => $d->nama,
                'kecamatan' => $d->kecamatan?->nama ?? '-',
            ]);

        return [
            new PendudukTemplateSheet($desas->pluck('nama')),
            new ReferensiWilayahSheet($desas),
        ];
    }
}
