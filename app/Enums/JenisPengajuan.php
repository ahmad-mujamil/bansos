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

    public function getJenisOrganisasi(): array
    {
        return match ($this) {
            self::HIBAH => [
                JenisOrganisasi::PEMERINTAH_DAERAH,
                JenisOrganisasi::PEMERINTAH_PUSAT,
                JenisOrganisasi::BUMN,
                JenisOrganisasi::BUMD,
                JenisOrganisasi::ORMAS,
                JenisOrganisasi::PARTAI_POLITIK,
                JenisOrganisasi::BADAN_LEMBAGA,
            ],
            self::BANTUAN_KELOMPOK => [
                JenisOrganisasi::KELOMPOK,
            ],
            self::BANSOS => [
                JenisOrganisasi::LEMBAGA_NON_PEMERINTAH,
            ],
        };
    }

    public static function fromJenisOrganisasi(JenisOrganisasi|string|null $jenis): ?self
    {
        if ($jenis === null) {
            return null;
        }
        $jenis = $jenis instanceof JenisOrganisasi ? $jenis : JenisOrganisasi::tryFrom($jenis);
        if ($jenis === null) {
            return null;
        }
        foreach (self::cases() as $case) {
            if (in_array($jenis, $case->getJenisOrganisasi(), true)) {
                return $case;
            }
        }
        return null;
    }
}
