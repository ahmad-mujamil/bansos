<?php

namespace App\Enums;

enum JenisDokumen: string
{
    case NPWP = "NPWP";
    case AKTA = "AKTA";
    case SK = "SK";
    case STRUKTUR = "SO";

    public function getDescription(): string
    {
        return match ($this) {
            self::NPWP => "NPWP",
            self::AKTA => "Akta Pendirian/Kepemilikan/Usaha",
            self::SK => "Surat Keputusan",
            self::STRUKTUR => "Struktur Organisasi",
        };
    }
}

