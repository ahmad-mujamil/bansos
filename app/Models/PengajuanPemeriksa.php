<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PengajuanPemeriksa extends Model
{
    public const MIN_VERIFIKASI_COUNT = 3;
    public const MAX_VERIFIKASI_COUNT = 20;

    use HasUuids;

    protected $table = 'pengajuan_pemeriksa';

    protected $keyType = 'string';

    protected $guarded = [];

    public function pengajuan(): BelongsTo
    {
        return $this->belongsTo(Pengajuan::class);
    }
}
