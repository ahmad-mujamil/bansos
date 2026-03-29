<?php

namespace App\Enums;

enum SatuanBarang: string
{
    case BUAH = 'buah';
    case PIECES = 'pieces';
    case PAKET = 'paket';
    case KG = 'kg';
    case HARI = 'hari';
    case LITER = 'liter';
    case METER = 'meter';
    case ORANG = 'orang';
    case UNIT = 'unit';
    case SET = 'set';
    case LEMBAR = 'lembar';
    case SAK = 'sak';
    case DUS = 'dus';
    case BOX = 'box';
    case TON = 'ton';
    case BAL = 'bal';
    case RIM = 'rim';
    case BULAN = 'bulan';

    public function getDescription(): string
    {
        return match ($this) {
            self::BUAH => 'Buah',
            self::PIECES => 'Pcs / pieces',
            self::PAKET => 'Paket',
            self::KG => 'Kg',
            self::HARI => 'Hari',
            self::LITER => 'Liter',
            self::METER => 'Meter',
            self::ORANG => 'Orang',
            self::UNIT => 'Unit',
            self::SET => 'Set',
            self::LEMBAR => 'Lembar',
            self::SAK => 'Sak',
            self::DUS => 'Dus',
            self::BOX => 'Box',
            self::TON => 'Ton',
            self::BAL => 'Bal',
            self::RIM => 'Rim',
            self::BULAN => 'Bulan',
        };
    }
}
