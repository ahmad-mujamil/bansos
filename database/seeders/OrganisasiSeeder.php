<?php

namespace Database\Seeders;

use App\Enums\JenisOrganisasi;
use App\Models\Desa;
use App\Models\Kecamatan;
use App\Models\Opd;
use App\Models\Organisasi;
use App\Models\User;
use Illuminate\Database\Seeder;

class OrganisasiSeeder extends Seeder
{
    public function run(): void
    {
        $opd = Opd::query()->first();
        $kecamatan = Kecamatan::query()->first();
        $desa = Desa::query()->where('kecamatan_id', $kecamatan?->id)->first()
            ?? Desa::query()->first();

        // Ambil user dengan role 'user' sebagai pemilik organisasi
        $users = User::query()->where('role', 'user')->get();

        if ($users->count() < 5 || !$opd || !$kecamatan || !$desa) {
            $this->command->warn('OrganisasiSeeder: pastikan OpdSeeder, KecamatanSeeder, dan UserSeeder sudah dijalankan (minimal 5 user dengan role "user").');
            return;
        }

        $data = [
            [
                'nama'            => 'Kelompok Tani Maju Bersama',
                'nomor'           => 'SK/001/DINSOS/2024',
                'tgl_pembentukan' => '2020-03-15',
                'jenis'           => JenisOrganisasi::KELOMPOK->value,
                'user_id'         => $users->get(0)->id,
            ],
            [
                'nama'            => 'Yayasan Peduli Lombok',
                'nomor'           => 'AHU-0012345.AH.01.04.2019',
                'tgl_pembentukan' => '2019-07-20',
                'jenis'           => JenisOrganisasi::YAYASAN->value,
                'user_id'         => $users->get(1)->id,
            ],
            [
                'nama'            => 'Kelompok Usaha Bersama Sejahtera',
                'nomor'           => 'SK/002/DINSOS/2023',
                'tgl_pembentukan' => '2023-01-10',
                'jenis'           => JenisOrganisasi::KELOMPOK->value,
                'user_id'         => $users->get(2)->id,
            ],
            [
                'nama'            => 'Masjid Al-Ikhlas Gerung',
                'nomor'           => 'SK/003/KEMENAG/2018',
                'tgl_pembentukan' => '2018-05-05',
                'jenis'           => JenisOrganisasi::TEMPAT_IBADAH->value,
                'user_id'         => $users->get(3)->id,
            ],
            [
                'nama'            => 'Organisasi Masyarakat Mandiri Lombok',
                'nomor'           => 'AHU-0098765.AH.01.07.2021',
                'tgl_pembentukan' => '2021-09-01',
                'jenis'           => JenisOrganisasi::ORGANISASI->value,
                'user_id'         => $users->get(4)->id,
            ],
        ];

        foreach ($data as $item) {
            Organisasi::query()->updateOrCreate(
                ['nomor' => $item['nomor']],
                array_merge($item, [
                    'kecamatan_id' => $kecamatan->id,
                    'desa_id'      => $desa->id,
                    'opd_id'       => $opd->id,
                    'is_active'    => true,
                ])
            );
        }
    }
}