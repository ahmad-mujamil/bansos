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
            ['nama' => 'Dinas Sosial Kabupaten Lombok Barat', 'alamat' => 'Jl. Raya Gerung No. 1, Gerung, Kabupaten Lombok Barat', 'no_telp' => '081234567890', 'email' => 'dinsos@lobar.go.id', 'website' => 'https://www.lobar.go.id','fax' => '081234567890', 'kepala_opd' => 'Ahmad Fauzi'],
            ['nama' => 'Dinas Pertanian dan Perkebunan', 'alamat' => 'Jl. Raya Gerung No. 2, Gerung, Kabupaten Lombok Barat', 'no_telp' => '081234567891', 'email' => 'dinatan@lobar.go.id', 'website' => 'https://www.lobar.go.id','fax' => '081234567891', 'kepala_opd' => 'Ahmad Fauzi'],
            ['nama' => 'Dinas Ketahanan Pangan', 'alamat' => 'Jl. Raya Gerung No. 3, Gerung, Kabupaten Lombok Barat', 'no_telp' => '081234567892', 'email' => 'dinasketahanan@lobar.go.id', 'website' => 'https://www.lobar.go.id','fax' => '081234567892', 'kepala_opd' => 'Ahmad Fauzi'],
            ['nama' => 'Dinas Pemberdayaan Masyarakat dan Desa', 'alamat' => 'Jl. Raya Gerung No. 4, Gerung, Kabupaten Lombok Barat', 'no_telp' => '081234567893', 'email' => 'dinaspbd@lobar.go.id', 'website' => 'https://www.lobar.go.id','fax' => '081234567893', 'kepala_opd' => 'Ahmad Fauzi'],
            ['nama' => 'Dinas Koperasi, Usaha Kecil dan Menengah', 'alamat' => 'Jl. Raya Gerung No. 5, Gerung, Kabupaten Lombok Barat', 'no_telp' => '081234567894', 'email' => 'dinaskop@lobar.go.id', 'website' => 'https://www.lobar.go.id','fax' => '081234567894', 'kepala_opd' => 'Ahmad Fauzi'],
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
