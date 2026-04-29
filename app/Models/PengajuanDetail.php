<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PengajuanDetail extends Model
{
    use HasUuids;

    protected $table = 'pengajuan_detail';

    protected $keyType = 'string';

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'nilai' => 'decimal:2',
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
