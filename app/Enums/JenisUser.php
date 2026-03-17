<?php

namespace App\Enums;

enum JenisUser: string
{
    case INDIVIDUAL = "IND";
    case KELOMPOK = "KLP";


    public function getDescription(): string
    {
        return match ($this) {
            self::INDIVIDUAL => "Individual",
            self::KELOMPOK => "Kelompok",
        };
    }
}
