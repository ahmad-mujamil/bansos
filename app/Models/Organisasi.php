<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Organisasi extends Model
{
    use HasUuids;
    protected $table = 'organisasi';
    protected $keyType = 'string';

    protected function casts(): array
    {
        return [
            'tgl_pembentukan' => 'date:Y-m-d'
        ];
    }

    public function organisasiDetail(): HasMany
    {
        return $this->hasMany(OrganisasiDetail::class);
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
