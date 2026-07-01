<?php

namespace Database\Seeders;

use App\Models\TahunAnggaran;
use Illuminate\Database\Seeder;

class TahunAnggaranSeeder extends Seeder
{
    public function run(): void
    {
        $tahunSekarang = (int) date('Y');

        // Seed tahun berjalan dan dua tahun sebelumnya.
        for ($tahun = $tahunSekarang; $tahun <= $tahunSekarang; $tahun++) {
            TahunAnggaran::query()->updateOrCreate(
                ['tahun' => $tahun],
                [
                    'label' => 'TA '.$tahun,
                    'is_terkunci' => false,
                ]
            );
        }
    }
}
