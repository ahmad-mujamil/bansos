<?php

namespace App\Enums;

enum RoleUser: string
{
    case SUPER = 'super';
    case ADMIN = 'admin';
    case OPD = 'opd';
    case USER = 'user';

    public function getDescription(): string
    {
        return match ($this) {
            self::SUPER => 'Super Admin',
            self::ADMIN => 'Administrator',
            self::OPD => 'OPD',
            self::USER => 'User/Masyarakat',
        };
    }

    public function getPermissions(): array
    {
        return match ($this) {
            self::SUPER => ['*'],
            self::ADMIN => [
                'ADMIN', 'PENGGUNA',
                'LANDING-PAGE', 'BERITA', 'SLIDER',
                'MASTER_DATA', 'PENDUDUK', 'KELOMPOK_MASYARAKAT', 'JENIS_BANTUAN', 'OPD',
                'WILAYAH_ADMINISTRASI', 'KECAMATAN', 'DESA',
                "LANDING_PAGE","BERITA","SLIDER", "GALERI","ALUR_BANTUAN","PROFILE"
            ],
            self::OPD => [
                'SIDE_MASTER_DATA', 'KELOMPOK_MASYARAKAT',"USER_KELOMPOK",
                'SIDE_VERIFIKASI', 'VERIFY_PENGAJUAN', 'VERIFY_PENGGUNA',
                'BAST_DAN_BLACKLIST', 'BLACKLIST', 'BAST',
                'MONITORING_DAN_REALISASI', 'MONITORING', 'REALISASI_BANTUAN',
            ],
            self::USER => [
                'BANTUAN', 'PENGAJUAN', 'REALISASI',
                'PROFILE_USER', 'PROFILE_USER_DETAIL',
            ],
        };
    }
}
