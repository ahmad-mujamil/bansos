<?php

namespace App\Models;

use App\Enums\KategoriBantuan;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class JenisBantuan extends Model
{
    use HasUuids;
    protected $table = 'jenis_bantuan';
    protected $keyType = 'string';

    protected function casts(): array
    {
        return [
            'kategori' => KategoriBantuan::class,
        ];
    }
}
