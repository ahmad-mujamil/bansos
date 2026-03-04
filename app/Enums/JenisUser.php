<?php

namespace App\Enums;

enum JenisUser: string
{
    case INDIVIDUAL = "IND";
    case KELOMPOK = "KLP";
    case INSTANSI = "INS";
    case ORGANISASI = "ORG";
    case YAYASAN = "YYS";
    case TEMPAT_IBADAH = "TIB";

    public function getDescription(): string
    {
        return match ($this) {
            self::INDIVIDUAL => "Individual",
            self::KELOMPOK => "Kelompok",
            self::INSTANSI => "Instansi",
            self::ORGANISASI => "Organisasi",
            self::YAYASAN => "Yayasan",
            self::TEMPAT_IBADAH => "Tempat Ibadah",
        };
    }
}
