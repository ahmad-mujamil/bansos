<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PengajuanAnggotaSnapshot extends Model
{
    use HasUuids;

    protected $table = 'pengajuan_anggota_snapshot';

    protected $keyType = 'string';

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'is_valid' => 'boolean',
        ];
    }

    public function snapshot(): BelongsTo
    {
        return $this->belongsTo(PengajuanKelompokSnapshot::class, 'snapshot_id');
    }

    public function penduduk(): BelongsTo
    {
        return $this->belongsTo(Penduduk::class);
    }
}
