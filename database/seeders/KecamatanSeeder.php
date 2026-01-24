<?php

namespace Database\Seeders;

use App\Enums\Kecamatan as KecamatanEnum;
use App\Models\Kecamatan;
use App\Models\Desa;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class KecamatanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (KecamatanEnum::cases() as $kecamatanCase) {
            $kecamatan = Kecamatan::updateOrCreate([
                'nama' => $kecamatanCase->value,
            ]);

            foreach ($kecamatanCase->desaKelurahan() as $desaName) {
                Desa::updateOrCreate([
                    'nama' => $desaName,
                    'kecamatan_id' => $kecamatan->id,
                ]);
            }
        }
    }
}
