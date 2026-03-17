<?php

namespace Database\Seeders;

use App\Enums\JenisUser;
use App\Models\Desa;
use App\Models\User;
use App\Models\UserDetail;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $desa = Desa::query()->first();

        $users = [
            [
                'user' => [
                    'nama'       => 'Ahmad Fauzi',
                    'email'      => 'ahmad.fauzi@example.com',
                    'username'   => 'ahmad_fauzi',
                    'password'   => bcrypt('password'),
                    'role'       => 'user',
                    'is_active'  => true,
                ],
                'detail' => [
                    'type'        => JenisUser::INDIVIDUAL->value,
                    'nama_user'   => 'Ahmad Fauzi',
                    'nik'         => '5201010101850001',
                    'nama_personal' => 'Ahmad Fauzi',
                    'alamat'      => 'Jl. Raya Gerung No. 10, Gerung',
                    'desa_id'     => $desa?->id,
                    'phone'       => '081234567801',
                    'verification_status' => 'approved',
                ],
            ],
            [
                'user' => [
                    'nama'       => 'Siti Rahayu',
                    'email'      => 'siti.rahayu@example.com',
                    'username'   => 'siti_rahayu',
                    'password'   => bcrypt('password'),
                    'role'       => 'user',
                    'is_active'  => true,
                ],
                'detail' => [
                    'type'        => JenisUser::INDIVIDUAL->value,
                    'nama_user'   => 'Siti Rahayu',
                    'nik'         => '5201010201900002',
                    'nama_personal' => 'Siti Rahayu',
                    'alamat'      => 'Jl. Kediri Barat No. 5, Kediri',
                    'desa_id'     => $desa?->id,
                    'phone'       => '081234567802',
                    'verification_status' => 'approved',
                ],
            ],
            [
                'user' => [
                    'nama'       => 'Budi Hartono',
                    'email'      => 'budi.hartono@example.com',
                    'username'   => 'budi_hartono',
                    'password'   => bcrypt('password'),
                    'role'       => 'user',
                    'is_active'  => true,
                ],
                'detail' => [
                    'type'        => JenisUser::ORGANISASI->value,
                    'nama_user'   => 'Kelompok Tani Maju Bersama',
                    'nama_lembaga' => 'Kelompok Tani Maju Bersama',
                    'alamat'      => 'Dusun Karang Baru, Narmada',
                    'desa_id'     => $desa?->id,
                    'phone'       => '081234567803',
                    'verification_status' => 'approved',
                ],
            ],
            [
                'user' => [
                    'nama'       => 'Dewi Lestari',
                    'email'      => 'dewi.lestari@example.com',
                    'username'   => 'dewi_lestari',
                    'password'   => bcrypt('password'),
                    'role'       => 'user',
                    'is_active'  => true,
                ],
                'detail' => [
                    'type'        => JenisUser::INDIVIDUAL->value,
                    'nama_user'   => 'Dewi Lestari',
                    'nik'         => '5201010301880003',
                    'nama_personal' => 'Dewi Lestari',
                    'alamat'      => 'Jl. Sekotong Tengah No. 15',
                    'desa_id'     => $desa?->id,
                    'phone'       => '081234567804',
                    'verification_status' => 'pending',
                ],
            ],
            [
                'user' => [
                    'nama'       => 'Hendra Gunawan',
                    'email'      => 'hendra.gunawan@example.com',
                    'username'   => 'hendra_gunawan',
                    'password'   => bcrypt('password'),
                    'role'       => 'user',
                    'is_active'  => true,
                ],
                'detail' => [
                    'type'        => JenisUser::ORGANISASI->value,
                    'nama_user'   => 'Yayasan Peduli Lombok',
                    'nama_lembaga' => 'Yayasan Peduli Lombok',
                    'alamat'      => 'Jl. Gunung Sari No. 22',
                    'desa_id'     => $desa?->id,
                    'phone'       => '081234567805',
                    'verification_status' => 'approved',
                ],
            ],
        ];

        foreach ($users as $item) {
            $user = User::query()->updateOrCreate(
                ['email' => $item['user']['email']],
                $item['user']
            );

            UserDetail::query()->updateOrCreate(
                ['user_id' => $user->id],
                array_merge($item['detail'], ['user_id' => $user->id])
            );
        }
    }
}