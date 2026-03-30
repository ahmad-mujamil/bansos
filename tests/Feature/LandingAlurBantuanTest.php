<?php

use App\Enums\KategoriBantuan;
use App\Models\AlurBantuan;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\get;

uses(RefreshDatabase::class);

it('menampilkan alur bantuan dari konfigurasi admin di landing page', function () {
    $alurBantuan = AlurBantuan::query()->create([
        'kategori' => KategoriBantuan::BANSOS->value,
    ]);
    $alurBantuan->steps()->createMany([
        [
            'urutan' => 1,
            'judul' => '1. Registrasi Warga',
            'deskripsi' => 'Warga melakukan pendaftaran awal melalui sistem.',
            'icon' => 'person_search',
        ],
        [
            'urutan' => 2,
            'judul' => '2. Verifikasi Dokumen',
            'deskripsi' => 'Petugas memeriksa kelengkapan dokumen pengajuan.',
            'icon' => 'fact_check',
        ],
    ]);

    $response = get('/');

    $response
        ->assertSuccessful()
        ->assertSee('Bantuan Sosial')
        ->assertSee('1. Registrasi Warga')
        ->assertSee('Petugas memeriksa kelengkapan dokumen pengajuan.');
});
