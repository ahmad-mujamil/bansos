<?php

namespace App\Models;

use App\Enums\JabatanOrganisasi;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KelompokMasyarakatDetail extends Model
{
    use HasUuids;
    protected $table = 'kelompok_masyarakat_detail';
    protected $keyType = 'string';

    protected function casts(): array
    {
        return [
            'jabatan' => JabatanOrganisasi::class,

        ];
    }

    public function kelompokMasyarakat(): BelongsTo
    {
        return $this->belongsTo(KelompokMasyarakat::class);
    }
    public function penduduk(): BelongsTo
    {
        return $this->belongsTo(Penduduk::class);
    }

}
