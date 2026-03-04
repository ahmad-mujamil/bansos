<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Organisasi;

class Opd extends Model
{
    use HasUuids;
    protected $table = 'opd';
    protected $keyType = 'string';

    public function user(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function organisasi(): HasMany
    {
        return $this->hasMany(Organisasi::class);
    }
}

