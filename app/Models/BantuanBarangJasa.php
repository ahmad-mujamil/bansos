<?php

namespace App\Models;

use App\Enums\PengajuanStatus;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BantuanBarangJasa extends Model
{
    use HasUuids;

    protected $table = 'bantuan_barang_jasa';

    protected $keyType = 'string';

    protected $guarded = [];

    public function verifikasiPengajuan(): BelongsTo
    {
        return $this->belongsTo(VerifikasiPengajuan::class);
    }

}
