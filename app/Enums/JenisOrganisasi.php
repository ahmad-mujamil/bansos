<?php

namespace App\Enums;

enum JenisOrganisasi: string
{
    case KELOMPOK = "KLP";
    case ORGANISASI = "ORG";
    case TEMPAT_IBADAH = "TIB";
    case INSTANSI = "INS";
    case YAYASAN = "YYS";

    public function getDescription(): string
    {
        return match ($this) {
            self::KELOMPOK => "Kelompok Masyarakat",
            self::ORGANISASI => "Organisasi",
            self::TEMPAT_IBADAH => "Tempat Ibadah",
            self::INSTANSI => "Instansi",
            self::YAYASAN => "Yayasan",
        };
    }
}

