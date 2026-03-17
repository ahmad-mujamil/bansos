<?php

namespace Database\Seeders;

use App\Models\Opd;
use Illuminate\Database\Seeder;

class OpdSeeder extends Seeder
{
    public function run(): void
    {
        $opds = [
            ['nama' => 'Dinas Sosial Kabupaten Lombok Barat'],
            ['nama' => 'Dinas Pertanian dan Perkebunan'],
            ['nama' => 'Dinas Ketahanan Pangan'],
            ['nama' => 'Dinas Pemberdayaan Masyarakat dan Desa'],
            ['nama' => 'Dinas Koperasi, Usaha Kecil dan Menengah'],
        ];

        foreach ($opds as $opd) {
            Opd::query()->updateOrCreate(['nama' => $opd['nama']], $opd);
        }
    }
}