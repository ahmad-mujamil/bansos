<?php

namespace App\Enums;

enum LevelDesil: int
{
    case DESIL_1 =1;
    case DESIL_2 = 2;
    case DESIL_3 = 3;
    case DESIL_4 = 4;
    case DESIL_5 = 5;
    case DESIL_6 = 6;
    case DESIL_7 = 7;
    case DESIL_8 = 8;
    case DESIL_9 = 9;
    case DESIL_10 = 10;

    public function getDescription(): string
    {
        return match ($this) {
            self::DESIL_1 => "Sangat Miskin",
            self::DESIL_2 => "Miskin",
            self::DESIL_3 => "Hampir Miskin",
            self::DESIL_4 => "Rentan Miskin",
            self::DESIL_5 => "Pas-Pasan",
            self::DESIL_6 => "Hampir Mampu",
            self::DESIL_7 => "Menengah Kebawah",
            self::DESIL_8 => "Menengah",
            self::DESIL_9 => "Menengah Keatas",
            self::DESIL_10 => "Sejahtera",

        };
    }
}

