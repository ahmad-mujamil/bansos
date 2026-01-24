<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kecamatan extends Model
{
    use HasUuids;
    protected $table = 'kecamatan';
    protected $keyType = 'string';


    public function desa(): HasMany
    {
        return $this->hasMany(Desa::class);
    }
}
