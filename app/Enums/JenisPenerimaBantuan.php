<?php

namespace App\Enums;

enum JenisPenerimaBantuan: string
{
    case INDIVIDU = 'individu';
    case NON_INDIVIDU = 'non_individu';
    case KELUARGA = 'keluarga';

    public function getDescription(): string
    {
        return match ($this) {
            self::INDIVIDU=> 'Individu',
            self::KELUARGA => 'Keluarga',
            self::NON_INDIVIDU => 'Non Individu (Organisasi/Kelompok/Lembaga)',
        };
    }
}
