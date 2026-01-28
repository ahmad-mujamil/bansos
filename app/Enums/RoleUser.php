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
            self::SUPER, self::ADMIN => ["*"],
            self::OPD => [
                "MASTER_DATA",
                "KELOMPOK_MASYARAKAT",
                "LAPORAN","LAP_PENGAJUAN","LAP_REALISASI"
            ],
            self::USER => [
                "PENGUMUMAN",
                "MANAGEMENT_ANGGOTA",
                "DAFTAR_ANGGOTA",
                "PENEMPATAN",
                "TRANSAKSI",
                "BUY-TIKET",
                "BUY-PAKET",
                "KONFIRMASI_PEMBELIAN",
                "JARINGAN",
                "POHON_SPONSOR",
                "CARI_DOWNLINE",
                "WALLET",
                "SALDO",
                "WITHDRAW"
            ],
        };
    }
}
