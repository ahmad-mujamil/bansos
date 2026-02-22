<?php

namespace App\Enums;

enum JenisKelamin: string
{
    case LAKI_LAKI = "L";
    case PEREMPUAN = "P";

    public function getDescription(): string
    {
        return match ($this) {
            self::LAKI_LAKI => "Laki-laki",
            self::PEREMPUAN => "Perempuan",
        };
    }
}

