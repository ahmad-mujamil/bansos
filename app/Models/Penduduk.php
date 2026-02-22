<?php

namespace App\Models;

use App\Enums\JenisKelamin;
use App\Enums\LevelDesil;
use App\Enums\StatusPerkawinan;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Penduduk extends Model
{
    use HasUuids;
    protected $table = 'penduduk';
    protected $keyType = 'string';

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'tgl_lahir' => 'date',
            'status_perkawinan' => StatusPerkawinan::class,
            'level_desil' => LevelDesil::class,
            'jk' => JenisKelamin::class,
        ];
    }

    public function desa(): BelongsTo
    {
        return $this->belongsTo(Desa::class);
    }

    public function kecamatan(): BelongsTo
    {
        return $this->belongsTo(Kecamatan::class);
    }
}
