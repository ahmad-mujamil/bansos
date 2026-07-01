<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class TahunAnggaran extends Model
{
    use HasUuids;

    protected $table = 'tahun_anggaran';

    protected $keyType = 'string';

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'tahun' => 'integer',
            'is_terkunci' => 'boolean',
        ];
    }

    public function getLabelTampilAttribute(): string
    {
        return $this->label ?: 'TA '.$this->tahun;
    }
}
