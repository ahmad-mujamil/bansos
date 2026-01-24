<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KelompokMasyarakat extends Model
{
    use HasUuids;
    protected $table = 'kelompok_masyarakat';
    protected $keyType = 'string';

    protected function casts(): array
    {
        return [
            'tgl_pembentukan' => 'date:Y-m-d'
        ];
    }

    public function kecamatan(): BelongsTo
    {
        return $this->belongsTo(Kecamatan::class);
    }
    public function desa(): BelongsTo
    {
        return $this->belongsTo(Desa::class);
    }

    public function opd(): BelongsTo
    {
        return $this->belongsTo(Opd::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
