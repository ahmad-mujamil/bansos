<?php

namespace App\Models;

use App\Enums\MomenSnapshot;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PengajuanPenerimaSnapshot extends Model
{
    use HasUuids;

    protected $table = 'pengajuan_penerima_snapshot';

    protected $keyType = 'string';

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'momen' => MomenSnapshot::class,
            'nilai' => 'decimal:2',
            'is_valid' => 'boolean',
        ];
    }

    public function pengajuan(): BelongsTo
    {
        return $this->belongsTo(Pengajuan::class);
    }

    public function penduduk(): BelongsTo
    {
        return $this->belongsTo(Penduduk::class);
    }
}
