<?php

namespace App\Enums;

enum RoleUser:string {
    case SUPER = "super";
    case ADMIN = "admin";
    case OPD = "opd";
    case USER = "user";


    public function getDescription() : string
    {
        return match ($this) {
            self::SUPER => "Super Admin",
            self::ADMIN => "Administrator",
            self::OPD => "OPD",
            self::USER => "User",
        };
    }

    public function getPermissions() : array
    {
        return match ($this) {
            self::SUPER, self::ADMIN => ["*"],
            self::USER => [
                "PENGUMUMAN",
                "MANAGEMENT_ANGGOTA","DAFTAR_ANGGOTA","PENEMPATAN",
                "TRANSAKSI","BUY-TIKET","BUY-PAKET","KONFIRMASI_PEMBELIAN",
                "JARINGAN","POHON_SPONSOR","CARI_DOWNLINE",
                "WALLET","SALDO","WITHDRAW"
            ],
        };
    }

    public function getStatusUser() : array
    {
        return match ($this) {
            self::SUPER, self::ADMIN => [StatusUser::ADMIN_APP],
            self::OPD => [
                StatusUser::BUPATI,
                StatusUser::WAKIL_BUPATI,
                StatusUser::KADIS,
                StatusUser::SEKRETARIS,
                StatusUser::KABAG,
                StatusUser::KASIE
            ],
            self::USER => [
                StatusUser::PERORANGAN,
                StatusUser::ORGANISASI
            ],
        };
    }
}
