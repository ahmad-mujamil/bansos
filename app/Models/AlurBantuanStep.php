<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AlurBantuanStep extends Model
{
    use HasUuids;

    protected $table = 'alur_bantuan_step';

    protected $keyType = 'string';

    public $incrementing = false;

    protected $guarded = [];

    public function alurBantuan(): BelongsTo
    {
        return $this->belongsTo(AlurBantuan::class);
    }
}
