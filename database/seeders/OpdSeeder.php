<?php

namespace Database\Seeders;

use App\Models\Opd;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

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

        foreach ($opds as $opdData) {
            $opd = Opd::query()->updateOrCreate(['nama' => $opdData['nama']], $opdData);

            // Create user for each OPD
            $username = Str::slug($opd->nama, '_');
            $email = Str::slug($opd->nama, '_') . '@opd.lobar.go.id';

            User::query()->updateOrCreate(
                ['email' => $email],
                [
                    'nama' => $opd->nama,
                    'email' => $email,
                    'username' => $username,
                    'password' => bcrypt('password'),
                    'role' => 'opd',
                    'opd_id' => $opd->id,
                    'is_active' => true,
                ]
            );
        }
    }
}
