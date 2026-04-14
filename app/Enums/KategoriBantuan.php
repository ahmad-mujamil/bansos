<?php

namespace App\Enums;

enum KategoriBantuan: string
{
    case BANSOS = "bansos";
    case HIBAH = "hibah";
    case BANTUAN_KELOMPOK = "bantuan_kelompok";


    public function getDescription(): string
    {
        return match ($this) {
            self::BANSOS => "Bantuan Sosial",
            self::BANTUAN_KELOMPOK => "Bantuan Kelompok",
            self::HIBAH => "Hibah",

        };
    }

    public function getBadge(): string
    {
        return match ($this) {
            self::BANSOS => "<span class='badge bg-success'>Bantuan Sosial</span>",
            self::HIBAH => "<span class='badge bg-primary'>Hibah</span>",
            self::BANTUAN_KELOMPOK => "<span class='badge bg-warning'>Bantuan Kelompok</span>",
        };
    }
}

