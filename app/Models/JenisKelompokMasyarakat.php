<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class JenisKelompokMasyarakat extends Model
{
    use HasUuids;

    protected $table = 'jenis_kelompok_masyarakat';

    protected $keyType = 'string';

    protected $guarded = [];
}
