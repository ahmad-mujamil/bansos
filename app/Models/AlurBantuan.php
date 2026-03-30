<?php

namespace App\Models;

use App\Enums\KategoriBantuan;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AlurBantuan extends Model
{
    use HasUuids;

    protected $table = 'alur_bantuan';

    protected $keyType = 'string';

    public $incrementing = false;

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'kategori' => KategoriBantuan::class,
        ];
    }

    public function steps(): HasMany
    {
        return $this->hasMany(AlurBantuanStep::class)->orderBy('urutan');
    }
}
