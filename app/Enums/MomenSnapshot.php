<?php

namespace App\Enums;

enum MomenSnapshot: string
{
    case DIAJUKAN = 'diajukan';
    case DISETUJUI = 'disetujui';

    public function getDescription(): string
    {
        return match ($this) {
            self::DIAJUKAN => 'Saat Diajukan',
            self::DISETUJUI => 'Saat Disetujui',
        };
    }
}
