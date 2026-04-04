<?php

use App\Enums\JabatanOrganisasi;
use App\Enums\JenisOrganisasi;
use App\Enums\RoleUser;
use App\Livewire\CariPendudukByNik;
use App\Models\Desa;
use App\Models\Kecamatan;
use App\Models\Opd;
use App\Models\Organisasi;
use App\Models\OrganisasiDetail;
use App\Models\Penduduk;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Livewire\Livewire;

uses(RefreshDatabase::class);

function seedWilayahAndUsers(): array
{
    $opdA = Opd::query()->create([
        'id' => (string) Str::uuid(),
        'nama' => 'OPD A',
    ]);
    $opdB = Opd::query()->create([
        'id' => (string) Str::uuid(),
        'nama' => 'OPD B',
    ]);

    $kec = Kecamatan::query()->create([
        'id' => (string) Str::uuid(),
        'nama' => 'Kecamatan Test',
    ]);
    $desa = Desa::query()->create([
        'id' => (string) Str::uuid(),
        'nama' => 'Desa Test',
        'kecamatan_id' => $kec->id,
    ]);

    $admin = User::query()->create([
        'id' => (string) Str::uuid(),
        'nama' => 'Admin',
        'email' => 'admin@example.com',
        'username' => 'adminuser',
        'password' => Hash::make('password'),
        'role' => RoleUser::ADMIN,
        'is_active' => true,
    ]);

    $opdUser = User::query()->create([
        'id' => (string) Str::uuid(),
        'nama' => 'OPD User',
        'email' => 'opd@example.com',
        'username' => 'opduser',
        'password' => Hash::make('password'),
        'role' => RoleUser::OPD,
        'opd_id' => $opdA->id,
        'is_active' => true,
    ]);

    return [$opdA, $opdB, $kec, $desa, $admin, $opdUser];
}

it('denies guests the cari penduduk page', function () {
    $this->get(route('penduduk.cari-by-nik'))->assertRedirect();
});

it('denies regular users the cari penduduk page', function () {
    $user = User::query()->create([
        'id' => (string) Str::uuid(),
        'nama' => 'User',
        'email' => 'user@example.com',
        'username' => 'plainuser',
        'password' => Hash::make('password'),
        'role' => RoleUser::USER,
        'is_active' => true,
    ]);

    $this->actingAs($user)->get(route('penduduk.cari-by-nik'))->assertUnauthorized();
});

it('shows penduduk and kelompok cards for admin after search', function () {
    [$opdA, $opdB, $kec, $desa, $admin] = seedWilayahAndUsers();

    $penduduk = Penduduk::query()->create([
        'id' => (string) Str::uuid(),
        'nik' => '3201010101010001',
        'nama' => 'Budi Tester',
        'alamat' => 'Jl. Contoh',
        'jk' => 'L',
        'kecamatan_id' => $kec->id,
        'desa_id' => $desa->id,
    ]);

    $organisasi = Organisasi::query()->create([
        'id' => (string) Str::uuid(),
        'nama' => 'Kelompok Tani Makmur',
        'nomor' => 'SK-TEST-001',
        'tgl_pembentukan' => now()->toDateString(),
        'jenis' => JenisOrganisasi::KELOMPOK->value,
        'desa_id' => $desa->id,
        'kecamatan_id' => $kec->id,
        'opd_id' => $opdA->id,
        'user_id' => $admin->id,
        'is_active' => true,
        'is_blacklist' => false,
    ]);

    OrganisasiDetail::query()->create([
        'organisasi_id' => $organisasi->id,
        'penduduk_id' => $penduduk->id,
        'jabatan' => JabatanOrganisasi::ANGGOTA->value,
    ]);

    Livewire::actingAs($admin)
        ->test(CariPendudukByNik::class)
        ->set('nik', '3201010101010001')
        ->call('search')
        ->assertSet('hasSearched', true)
        ->assertSee('Budi Tester')
        ->assertSee('Kelompok Tani Makmur');
});

it('filters kelompok by OPD for opd users', function () {
    [$opdA, $opdB, $kec, $desa, $admin, $opdUser] = seedWilayahAndUsers();

    $penduduk = Penduduk::query()->create([
        'id' => (string) Str::uuid(),
        'nik' => '3201010101010002',
        'nama' => 'Siti',
        'alamat' => 'Jl. Contoh',
        'jk' => 'P',
        'kecamatan_id' => $kec->id,
        'desa_id' => $desa->id,
    ]);

    $orgA = Organisasi::query()->create([
        'id' => (string) Str::uuid(),
        'nama' => 'Kelompok OPD A',
        'nomor' => 'SK-A-001',
        'tgl_pembentukan' => now()->toDateString(),
        'jenis' => JenisOrganisasi::KELOMPOK->value,
        'desa_id' => $desa->id,
        'kecamatan_id' => $kec->id,
        'opd_id' => $opdA->id,
        'user_id' => $admin->id,
        'is_active' => true,
        'is_blacklist' => false,
    ]);

    $orgB = Organisasi::query()->create([
        'id' => (string) Str::uuid(),
        'nama' => 'Kelompok OPD B',
        'nomor' => 'SK-B-001',
        'tgl_pembentukan' => now()->toDateString(),
        'jenis' => JenisOrganisasi::KELOMPOK->value,
        'desa_id' => $desa->id,
        'kecamatan_id' => $kec->id,
        'opd_id' => $opdB->id,
        'user_id' => $admin->id,
        'is_active' => true,
        'is_blacklist' => false,
    ]);

    OrganisasiDetail::query()->create([
        'organisasi_id' => $orgA->id,
        'penduduk_id' => $penduduk->id,
        'jabatan' => JabatanOrganisasi::KETUA->value,
    ]);
    OrganisasiDetail::query()->create([
        'organisasi_id' => $orgB->id,
        'penduduk_id' => $penduduk->id,
        'jabatan' => JabatanOrganisasi::ANGGOTA->value,
    ]);

    Livewire::actingAs($opdUser)
        ->test(CariPendudukByNik::class)
        ->set('nik', '3201010101010002')
        ->call('search')
        ->assertSee('Kelompok OPD A')
        ->assertDontSee('Kelompok OPD B');
});
