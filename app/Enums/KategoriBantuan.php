<?php

namespace App\Enums;

enum KategoriBantuan: string
{
    case BANSOS = "Bansos";
    case HIBAH = "Hibah";
    case SUBSIDI = "Subsidi";
    case PINJAMAN = "Pinjaman";
    case LAINNYA = "Lainnya";


    public function getDescription(): string
    {
        return match ($this) {
            self::BANSOS => "Bantuan Sosial",
            self::HIBAH => "Hibah",
            self::SUBSIDI => "Subsidi",
            self::PINJAMAN => "Pinjaman",
            self::LAINNYA => "Lainnya",
        };
    }

    public function getBadge(): string
    {
        return match ($this) {
            self::BANSOS => "<span class='badge bg-success'>Bantuan Sosial</span>",
            self::HIBAH => "<span class='badge bg-primary'>Hibah</span>",
            self::SUBSIDI => "<span class='badge bg-warning'>Subsidi</span>",
            self::PINJAMAN => "<span class='badge bg-info'>Pinjaman</span>",
            self::LAINNYA => "<span class='badge bg-secondary'>Lainnya</span>",
        };
    }
}

