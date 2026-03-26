<?php

namespace App\Enums;

enum JenisUser: string
{
    case INDIVIDUAL = 'IND';
    case KELOMPOK = 'KLP';
    case ORGANISASI = 'ORG';

    public function getDescription(): string
    {
        return match ($this) {
            self::INDIVIDUAL => 'Individual',
            self::KELOMPOK => 'Kelompok',
            self::ORGANISASI => 'Organisasi'
        };
    }
}
