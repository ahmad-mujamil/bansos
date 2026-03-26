<?php

namespace App\Enums;

enum RupaBantuan: string
{
    case BARANG = 'barang';
    case JASA = 'jasa';
    case UANG = 'uang';

    public function getDescription(): string
    {
        return match ($this) {
            self::BARANG => 'Barang',
            self::JASA => 'Jasa',
            self::UANG => 'Uang',
        };
    }
}
