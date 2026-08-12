<?php

namespace App\Enums;

enum JenisOrganisasi: string
{
    case KELOMPOK = "KLP";

    case PEMERINTAH_PUSAT = "PMP";
    case PEMERINTAH_DAERAH = "PMD";
    case BUMN = "BUMN";
    case BUMD = "BUMD";

    case BADAN_LEMBAGA = "BDL";
    case ORMAS = "ORM";
    case PARTAI_POLITIK = "PARPOL";
    case LEMBAGA_NON_PEMERINTAH = "LNP";
    case UMKM = "UMKM";
    case PERTANIAN = "PTN"; 


    public function getDescription(): string
    {
        return match ($this) {
            self::KELOMPOK => "Kelompok Masyarakat",
            self::BADAN_LEMBAGA => "Badan & Lembaga",
            self::ORMAS => "Organisasi Masyarakat",
            self::PARTAI_POLITIK => "Partai Politik",
            self::PEMERINTAH_PUSAT => "Pemerintah Pusat",
            self::PEMERINTAH_DAERAH => "Pemerintah Daerah",
            self::BUMN => "BUMN",
            self::BUMD => "BUMD",
            self::LEMBAGA_NON_PEMERINTAH => "Lembaga Non Pemerintah",
            self::UMKM => "Usaha Mikro, Kecil, dan Menengah",
            self::PERTANIAN => "Kelompok Pertanian",
        };
    }
}

