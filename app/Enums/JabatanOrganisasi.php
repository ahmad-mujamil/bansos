<?php

namespace App\Enums;

enum JabatanOrganisasi: string
{
    case KETUA = "Ketua";
    case WAKIL = "Wakil Ketua";
    case SEKRETARIS = "Sekretaris";
    case BENDAHARA = "Bendahara";
    case ADMIN = "Admin";
    case ANGGOTA = "Anggota";

    public function getDescription(): string
    {
        return match ($this) {
            self::KETUA => "Ketua",
            self::WAKIL => "Wakil Ketua",
            self::SEKRETARIS => "Sekretaris",
            self::BENDAHARA => "Bendahara",
            self::ANGGOTA => "Anggota",
            self::ADMIN => "Admin",
        };
    }
}

