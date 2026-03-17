<?php

namespace App\Enums;

enum JenisUser: string
{
    case INDIVIDUAL = "IND";
    case ORGANISASI = "ORG";

    public function getDescription(): string
    {
        return match ($this) {
            self::INDIVIDUAL => "Individual",
            self::ORGANISASI => "Kelompok/Organisasi",
        };
    }
}
