<?php

namespace App\Models;

use App\Enums\MomenSnapshot;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PengajuanKelompokSnapshot extends Model
{
    use HasUuids;

    protected $table = 'pengajuan_kelompok_snapshot';

    protected $keyType = 'string';

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'momen' => MomenSnapshot::class,
            'tgl_pembentukan' => 'date:Y-m-d',
        ];
    }

    public function pengajuan(): BelongsTo
    {
        return $this->belongsTo(Pengajuan::class);
    }

    public function anggota(): HasMany
    {
        return $this->hasMany(PengajuanAnggotaSnapshot::class, 'snapshot_id');
    }

    public function dibekukanOleh(): BelongsTo
    {
        return $this->belongsTo(User::class, 'dibekukan_oleh');
    }
}
