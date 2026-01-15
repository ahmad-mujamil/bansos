<?php

namespace App\Enums;

enum JenisUsaha: string
{
    case UMKM_KECIL = "UMKM kecil";
    case PETANI = "Petani";
    case NELAYAN = "Nelayan";
    case WARGA_KURANG_MAMPU = "Warga Kurang Mampu";

    public function getDescription(): string
    {
        return match ($this) {
            self::UMKM_KECIL => "UMKM kecil",
            self::PETANI => "Petani",
            self::NELAYAN => "Nelayan",
            self::WARGA_KURANG_MAMPU => "Warga Kurang Mampu",
        };
    }
}

