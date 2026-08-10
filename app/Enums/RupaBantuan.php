<?php

namespace App\Enums;

enum RupaBantuan: string
{
    case BARANG = 'barang';
    case JASA = 'jasa';
    case UANG = 'uang';
    case SUBSIDI_BUNGA = 'subsidi_bunga';

    public function getDescription(): string
    {
        return match ($this) {
            self::BARANG => 'Barang',
            self::JASA => 'Jasa',
            self::UANG => 'Uang',
            self::SUBSIDI_BUNGA => 'Subsidi Bunga',
        };
    }
}
