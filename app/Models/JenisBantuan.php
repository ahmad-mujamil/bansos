<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class JenisBantuan extends Model
{
    use HasUuids;
    protected $table = 'jenis_bantuan';
    protected $keyType = 'string';
}
