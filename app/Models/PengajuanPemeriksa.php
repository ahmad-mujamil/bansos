<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PengajuanPemeriksa extends Model
{
    use HasUuids;

    protected $table = 'pengajuan_pemeriksa';

    protected $keyType = 'string';

    protected $guarded = [];

    public function pengajuan(): BelongsTo
    {
        return $this->belongsTo(Pengajuan::class);
    }
}
