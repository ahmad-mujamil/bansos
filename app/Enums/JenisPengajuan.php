<?php

namespace App\Enums;

enum JenisPengajuan: string
{
    case HIBAH = 'hibah';
    case BANTUAN_KELOMPOK = 'bantuan_kelompok';
    case BANSOS = 'bansos';

    public function getDescription(): string
    {
        return match ($this) {
            self::HIBAH => 'Hibah',
            self::BANTUAN_KELOMPOK => 'Bantuan Kelompok',
            self::BANSOS => 'Bansos',
        };
    }
}
