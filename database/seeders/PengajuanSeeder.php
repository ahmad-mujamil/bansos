<?php

namespace Database\Seeders;

use App\Enums\PengajuanStatus;
use App\Models\JenisBantuan;
use App\Models\Opd;
use App\Models\Organisasi;
use App\Models\Pengajuan;
use App\Models\User;
use Illuminate\Database\Seeder;

class PengajuanSeeder extends Seeder
{
    public function run(): void
    {
        $opd = Opd::query()->first();
        $users = User::query()->where('role', 'user')->get();
        $organisasis = Organisasi::query()->get();
        $jenisBantuans = JenisBantuan::query()->get();

        if ($users->isEmpty() || $organisasis->isEmpty() || $jenisBantuans->isEmpty() || !$opd) {
            $this->command->warn('PengajuanSeeder: pastikan OpdSeeder, UserSeeder, OrganisasiSeeder, dan JenisBantuanSeeder sudah dijalankan terlebih dahulu.');
            return;
        }

        $data = [
            [
                'kode_pengajuan'  => 'PJN-2024-001',
                'jenis_bantuan_id' => $jenisBantuans->where('kategori', 'bantuan_kelompok')->first()?->id ?? $jenisBantuans->first()->id,
                'judul'           => 'Pengajuan Bantuan Alat Pertanian Kelompok Tani Maju Bersama',
                'lokasi'          => 'Desa Gerung Selatan, Kecamatan Gerung',
                'nilai'           => 25000000,
                'status'          => PengajuanStatus::DIAJUKAN->value,
                'user_id'         => $users->first()->id,
                'opd_id'          => $opd->id,
                'organisasi_id'   => $organisasis->first()->id,
                'catatan'         => 'Pengajuan bantuan alat pertanian berupa hand tractor dan pompa air untuk kelompok tani.',
            ],
            [
                'kode_pengajuan'  => 'PJN-2024-002',
                'jenis_bantuan_id' => $jenisBantuans->where('kategori', 'hibah')->first()?->id ?? $jenisBantuans->first()->id,
                'judul'           => 'Pengajuan Hibah Modal Usaha Yayasan Peduli Lombok',
                'lokasi'          => 'Jl. Raya Gerung, Lombok Barat',
                'nilai'           => 50000000,
                'status'          => PengajuanStatus::DISETUJUI->value,
                'user_id'         => $users->get(1)?->id ?? $users->first()->id,
                'opd_id'          => $opd->id,
                'organisasi_id'   => $organisasis->get(1)?->id ?? $organisasis->first()->id,
                'catatan'         => 'Pengajuan hibah modal usaha untuk pemberdayaan masyarakat kurang mampu.',
            ],
            [
                'kode_pengajuan'  => 'PJN-2024-003',
                'jenis_bantuan_id' => $jenisBantuans->where('kategori', 'bansos')->first()?->id ?? $jenisBantuans->first()->id,
                'judul'           => 'Pengajuan Bantuan Sosial Keluarga Tidak Mampu Desa Gerung',
                'lokasi'          => 'Desa Gerung Utara, Kecamatan Gerung',
                'nilai'           => 10000000,
                'status'          => PengajuanStatus::DRAFT->value,
                'user_id'         => $users->get(2)?->id ?? $users->first()->id,
                'opd_id'          => $opd->id,
                'organisasi_id'   => $organisasis->get(2)?->id ?? $organisasis->first()->id,
                'catatan'         => null,
            ],
            [
                'kode_pengajuan'  => 'PJN-2024-004',
                'jenis_bantuan_id' => $jenisBantuans->where('kategori', 'bantuan_kelompok')->first()?->id ?? $jenisBantuans->first()->id,
                'judul'           => 'Pengajuan Bantuan KUBE Sejahtera Narmada',
                'lokasi'          => 'Dusun Karang Baru, Kecamatan Narmada',
                'nilai'           => 30000000,
                'status'          => PengajuanStatus::DITOLAK->value,
                'user_id'         => $users->get(3)?->id ?? $users->first()->id,
                'opd_id'          => $opd->id,
                'organisasi_id'   => $organisasis->get(3)?->id ?? $organisasis->first()->id,
                'catatan'         => 'Dokumen persyaratan belum lengkap, mohon dilengkapi.',
            ],
            [
                'kode_pengajuan'  => 'PJN-2025-001',
                'jenis_bantuan_id' => $jenisBantuans->where('kategori', 'hibah')->first()?->id ?? $jenisBantuans->first()->id,
                'judul'           => 'Pengajuan Hibah Pembangunan Masjid Al-Ikhlas',
                'lokasi'          => 'Jl. Masjid Al-Ikhlas, Gerung',
                'nilai'           => 75000000,
                'status'          => PengajuanStatus::DIAJUKAN->value,
                'user_id'         => $users->get(0)?->id ?? $users->first()->id,
                'opd_id'          => $opd->id,
                'organisasi_id'   => $organisasis->get(3)?->id ?? $organisasis->first()->id,
                'catatan'         => 'Pengajuan hibah untuk renovasi dan perluasan masjid.',
            ],
            [
                'kode_pengajuan'  => 'PJN-2025-002',
                'jenis_bantuan_id' => $jenisBantuans->where('kategori', 'bansos')->first()?->id ?? $jenisBantuans->first()->id,
                'judul'           => 'Pengajuan PKH Masyarakat Kurang Mampu Kediri',
                'lokasi'          => 'Kecamatan Kediri, Lombok Barat',
                'nilai'           => 15000000,
                'status'          => PengajuanStatus::DRAFT->value,
                'user_id'         => $users->get(1)?->id ?? $users->first()->id,
                'opd_id'          => $opd->id,
                'organisasi_id'   => $organisasis->get(1)?->id ?? $organisasis->first()->id,
                'catatan'         => null,
            ],
        ];

        foreach ($data as $item) {
            Pengajuan::query()->updateOrCreate(
                ['kode_pengajuan' => $item['kode_pengajuan']],
                $item
            );
        }
    }
}