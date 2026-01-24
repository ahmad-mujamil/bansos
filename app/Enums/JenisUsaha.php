<?php

namespace App\Enums;

enum JenisUsaha: string
{
    case UMKM_KECIL = "UMKM kecil";
    case PETANI = "Petani";
    case NELAYAN = "Nelayan";
    case SENIMAN = "Seniman";

    public function getDescription(): string
    {
        return match ($this) {
            self::UMKM_KECIL => "UMKM kecil",
            self::PETANI => "Petani",
            self::NELAYAN => "Nelayan",
            self::SENIMAN => "Seniman",
        };
    }
}

