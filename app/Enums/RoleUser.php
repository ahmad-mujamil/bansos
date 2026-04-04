<?php

namespace App\Enums;

enum RoleUser: string
{
    case SUPER = 'super';
    case ADMIN = 'admin';
    case OPD = 'opd';
    case USER = 'user';
    case DUKCAPIL = 'dukcapil';

    public function getDescription(): string
    {
        return match ($this) {
            self::SUPER => 'Super Admin',
            self::ADMIN => 'Administrator',
            self::OPD => 'OPD',
            self::USER => 'User/Masyarakat',
            self::DUKCAPIL => 'Dukcapil',
        };
    }

    public function getPermissions(): array
    {
        return match ($this) {
            self::SUPER => ['*'],
            self::ADMIN => [
                'ADMIN', 'PENGGUNA',
                'LANDING-PAGE', 'BERITA', 'SLIDER',
                'MASTER_DATA', 'SIDE_MASTER_DATA', 'PENDUDUK', 'KELOMPOK_MASYARAKAT', 'CARI_PENDUDUK_NIK', 'JENIS_BANTUAN', 'OPD',
                'WILAYAH_ADMINISTRASI', 'KECAMATAN', 'DESA',
                'LANDING_PAGE', 'BERITA', 'SLIDER', 'GALERI', 'ALUR_BANTUAN', 'PROFILE',
            ],
            self::OPD => [
                'SIDE_MASTER_DATA', 'KELOMPOK_MASYARAKAT', 'USER_KELOMPOK', 'CARI_PENDUDUK_NIK',
                'SIDE_VERIFIKASI', 'VERIFY_PENGAJUAN', 'VERIFY_PENGGUNA',
                'BAST_DAN_BLACKLIST', 'BLACKLIST', 'BAST',
                'MONITORING_DAN_REALISASI', 'MONITORING', 'REALISASI_BANTUAN',
            ],
            self::USER => [
                'BANTUAN', 'PENGAJUAN', 'REALISASI',
                'PROFILE_USER', 'PROFILE_USER_DETAIL',
            ],
            self::DUKCAPIL => [
                'SIDE_VERIFIKASI', 'VERIFY_PENDUDUK',
            ],
        };
    }
}
