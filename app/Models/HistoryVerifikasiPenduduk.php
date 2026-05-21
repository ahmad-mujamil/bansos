<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HistoryVerifikasiPenduduk extends Model
{
    use HasUuids;

    protected $table = 'history_verifikasi_penduduk';

    protected $keyType = 'string';

    protected $guarded = [];

    public function penduduk(): BelongsTo
    {
        return $this->belongsTo(Penduduk::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function actionLabel(): string
    {
        return match ($this->action) {
            'input' => 'Input Data',
            'verifikasi' => 'Verifikasi',
            'perbaikan' => 'Perbaikan',
            default => ucfirst((string) $this->action),
        };
    }

    public function statusLabel(): ?string
    {
        return match ($this->status) {
            'diverifikasi' => 'Diverifikasi',
            'ditolak' => 'Ditolak',
            null, '' => null,
            default => ucfirst((string) $this->status),
        };
    }

    public function badgeClass(): string
    {
        return match (true) {
            $this->action === 'input' => 'bg-secondary',
            $this->action === 'perbaikan' => 'bg-info text-dark',
            $this->action === 'verifikasi' && $this->status === 'diverifikasi' => 'bg-success',
            $this->action === 'verifikasi' && $this->status === 'ditolak' => 'bg-danger',
            default => 'bg-light text-dark',
        };
    }
}
