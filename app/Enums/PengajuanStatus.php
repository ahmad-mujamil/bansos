<?php

namespace App\Enums;

enum PengajuanStatus: string
{
    case DRAFT = 'draft';
    case DIAJUKAN = 'diajukan';
    case DISETUJUI = 'disetujui';
    case DITOLAK = 'ditolak';

    public function getDescription(): string
    {
        return match ($this) {
            self::DRAFT    => 'Draft',
            self::DIAJUKAN => 'Diajukan',
            self::DISETUJUI => 'Disetujui',
            self::DITOLAK  => 'Ditolak',
        };
    }

    public function badgeColor(): string
    {
        return match ($this) {
            self::DRAFT    => 'secondary',
            self::DIAJUKAN => 'info',
            self::DISETUJUI => 'success',
            self::DITOLAK  => 'danger',
        };
    }
}
