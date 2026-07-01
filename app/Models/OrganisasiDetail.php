<?php

namespace App\Models;

use App\Enums\JabatanOrganisasi;
use App\Models\Concerns\BelongsToTahunAnggaran;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrganisasiDetail extends Model
{
    use BelongsToTahunAnggaran, HasUuids;
    protected $table = 'organisasi_detail';
    protected $keyType = 'string';

    protected $fillable = ['organisasi_id', 'penduduk_id', 'jabatan', 'tahun_anggaran'];

    protected function casts(): array
    {
        return [
            'jabatan' => JabatanOrganisasi::class,
            'tahun_anggaran' => 'integer',
        ];
    }

    public function organisasi(): BelongsTo
    {
        return $this->belongsTo(Organisasi::class);
    }
    public function penduduk(): BelongsTo
    {
        return $this->belongsTo(Penduduk::class);
    }

}
