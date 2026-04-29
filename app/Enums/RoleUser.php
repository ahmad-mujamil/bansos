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
                'LANDING-PAGE', 'BERITA', 'SLIDER',
                'DATA_KELOMPOK_MASYARAKAT','JENIS_KELOMPOK_MASYARAKAT','KELOMPOK_MASYARAKAT',
                'MASTER_DATA', 'PENDUDUK', 'JENIS_BANTUAN', 'OPD','PENGGUNA',
                'WILAYAH_ADMINISTRASI', 'KECAMATAN', 'DESA',
                'LANDING_PAGE', 'BERITA', 'SLIDER', 'GALERI', 'ALUR_BANTUAN', 'PROFILE',
                'LAPORAN', 'LAP_PENGAJUAN', 'LAP_REALISASI','LAP_KELOMPOK',
                "MONITORING_DAN_REALISASI",'MONITORING','REALISASI_BANTUAN','CARI_PENDUDUK_NIK'
            ],
            self::OPD => [
                'SIDE_MASTER_DATA', 'KELOMPOK_MASYARAKAT','PENDUDUK',
                'SIDE_VERIFIKASI', 'VERIFY_PENGAJUAN', 'VERIFY_PENGGUNA', 'PENGAJUAN_OPD',
                'SIDE_LAPORAN', 'SIDE_LAP_PENGAJUAN', 'SIDE_LAP_ANGGOTA_KELOMPOK', 'SIDE_LAP_KELOMPOK',
                "MONITORING_DAN_REALISASI",'MONITORING','REALISASI_BANTUAN','CARI_PENDUDUK_NIK',
                'BAST_DAN_BLACKLIST', 'BLACKLIST', 'BAST',
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
