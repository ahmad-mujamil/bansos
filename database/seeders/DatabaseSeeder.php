<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            KecamatanSeeder::class,
            PendudukSeeder::class,
            OpdSeeder::class,
            JenisBantuanSeeder::class,
            UserSeeder::class,
            OrganisasiSeeder::class,
            PengajuanSeeder::class,
        ]);

        User::query()->updateOrCreate([
            'email' => 'admin@bansos.test',
        ], [
            'nama' => 'Administrator',
            'role' => 'super',
            'is_active' => true,
            'username' => 'admin',
            'password' => bcrypt('1q2w3e4r5t'),
        ]);
    }
}
