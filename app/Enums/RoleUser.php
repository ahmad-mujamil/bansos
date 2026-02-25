<?php

namespace App\Enums;

enum RoleUser: string
{
    case SUPER = "super";
    case ADMIN = "admin";
    case OPD = "opd";
    case USER = "user";


    public function getDescription(): string
    {
        return match ($this) {
            self::SUPER => "Super Admin",
            self::ADMIN => "Administrator",
            self::OPD => "OPD",
            self::USER => "User",
        };
    }

    public function getPermissions(): array
    {
        return match ($this) {
            self::SUPER =>  ["*"],
            self::ADMIN => [
                "ADMIN","PENGGUNA","BLACKLIST","MONITORING",
                "MASTER_DATA","PENDUDUK","KELOMPOK_MASYARAKAT","JENIS_BANTUAN","OPD",
                "WILAYAH_ADMINISTRASI","KECAMATAN","DESA",
                "VERIFIKASI","VERIFY_PENGGUNA","VERIFY_BANSOS",
                "LAPORAN","LAP_PENGAJUAN","LAP_REALISASI"
            ],
            self::OPD => [
                "ADMIN","PENILAIAN_REALISASI","MONITORING",
                "MASTER_DATA", "PENDUDUK", "KELOMPOK_MASYARAKAT",
                "VERIFIKASI", "VERIFY_PENGGUNA","VERIFY_ORGANISASI", "VERIFY_BANSOS",
                "LAPORAN","LAP_PENGAJUAN","LAP_REALISASI",
            ],
            self::USER => [
                "BANTUAN_SOSIAL","PENGAJUAN","REALISASI",
                "PROFILE_USER","PROFILE_USER_DETAIL"
            ],
        };
    }
}
