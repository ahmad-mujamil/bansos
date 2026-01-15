<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Kecamatan extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'kecamatan';

    protected $fillable = [
        'nama',
    ];

    public function desa(): HasMany
    {
        return $this->hasMany(Desa::class);
    }
}

