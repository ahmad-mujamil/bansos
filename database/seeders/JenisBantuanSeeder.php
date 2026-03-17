<?php

namespace Database\Seeders;

use App\Enums\KategoriBantuan;
use App\Models\JenisBantuan;
use Illuminate\Database\Seeder;

class JenisBantuanSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            [
                'nama' => 'Bantuan Pangan Non Tunai (BPNT)',
                'keterangan' => 'Bantuan sosial berupa sembako untuk keluarga penerima manfaat',
                'kategori' => KategoriBantuan::BANSOS->value,
            ],
            [
                'nama' => 'Program Keluarga Harapan (PKH)',
                'keterangan' => 'Bantuan sosial bersyarat untuk keluarga miskin',
                'kategori' => KategoriBantuan::BANSOS->value,
            ],
            [
                'nama' => 'Hibah Sarana Pertanian',
                'keterangan' => 'Hibah alat dan sarana pertanian untuk kelompok tani',
                'kategori' => KategoriBantuan::HIBAH->value,
            ],
            [
                'nama' => 'Hibah Pemberdayaan Ekonomi',
                'keterangan' => 'Hibah modal usaha untuk kelompok masyarakat',
                'kategori' => KategoriBantuan::HIBAH->value,
            ],
            [
                'nama' => 'Bantuan Kelompok Tani',
                'keterangan' => 'Bantuan untuk kelompok tani dalam pengembangan pertanian',
                'kategori' => KategoriBantuan::BANTUAN_KELOMPOK->value,
            ],
            [
                'nama' => 'Bantuan Kelompok Usaha Bersama (KUBE)',
                'keterangan' => 'Bantuan untuk kelompok usaha bersama masyarakat',
                'kategori' => KategoriBantuan::BANTUAN_KELOMPOK->value,
            ],
        ];

        foreach ($data as $item) {
            JenisBantuan::query()->updateOrCreate(['nama' => $item['nama']], $item);
        }
    }
}