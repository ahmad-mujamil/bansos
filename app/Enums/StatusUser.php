<?php

namespace App\Enums;

enum StatusUser:int {
    case ADMIN_APP = 0;
    case BUPATI = 1;
    case WAKIL_BUPATI = 2;
    case KADIS = 3;
    case SEKRETARIS = 4;
    case KABAG = 5;
    case KASIE = 6;
    case PERORANGAN = 7;
    case ORGANISASI = 8;
    case KELOMPOK = 9;


    public function getDescription() : string
    {
        return match ($this) {
            self::ADMIN_APP => "Admin Aplikasi",
            self::BUPATI => "Bupati",
            self::WAKIL_BUPATI => "Wakil Bupati",
            self::KADIS => "Kepala Dinas",
            self::SEKRETARIS => "Sekretaris",
            self::KABAG => "Kepala Bagian",
            self::KASIE => "Kepala Seksi",
            self::PERORANGAN => "Perorangan",
            self::ORGANISASI => "Organisasi",
            self::KELOMPOK => "Kelompok",
        };
    }
}
