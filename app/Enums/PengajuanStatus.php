<?php

namespace App\Enums;

enum PengajuanStatus: string
{
    case DRAFT = 'draft';
    case DIAJUKAN = 'diajukan';
    case VERIFIKASI_ADMINISTRASI = 'verifikasi_administrasi';
    case VERIFIKASI_TEKNIS = 'verifikasi_teknis';
    case DISETUJUI = 'disetujui';
    case DITOLAK = 'ditolak';
    case REVISI = 'revisi';
    case SELESAI = 'selesai';

    public function getDescription(): string
    {
        return match ($this) {
            self::DRAFT => 'Draft',
            self::DIAJUKAN => 'Diajukan',
            self::VERIFIKASI_ADMINISTRASI => 'Verifikasi Administrasi',
            self::VERIFIKASI_TEKNIS => 'Verifikasi Teknis',
            self::DISETUJUI => 'Disetujui',
            self::DITOLAK => 'Ditolak',
            self::REVISI => 'Revisi',
            self::SELESAI => 'Selesai',
        };
    }
}
