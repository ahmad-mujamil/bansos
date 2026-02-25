<?php

namespace App\Models;

use App\Enums\JenisPengajuan;
use App\Enums\PengajuanStatus;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pengajuan extends Model
{
    use HasUuids;

    protected $table = 'pengajuan';

    protected $keyType = 'string';

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'jenis' => JenisPengajuan::class,
            'status' => PengajuanStatus::class,
            'verified_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function verifiedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function details(): HasMany
    {
        return $this->hasMany(PengajuanDetail::class);
    }

    public function logs(): HasMany
    {
        return $this->hasMany(PengajuanLog::class);
    }

    public function canEdit(): bool
    {
        return $this->status === PengajuanStatus::DRAFT || $this->status === PengajuanStatus::REVISI;
    }

    public function canSubmit(): bool
    {
        return $this->status === PengajuanStatus::DRAFT;
    }
}
