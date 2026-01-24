<?php

namespace App\Models;

use App\Enums\JabatanOrganisasi;
use App\Enums\JenisDokumen;
use App\Enums\JenisOrganisasi;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class OrganisasiDokumen extends Model implements HasMedia
{
    use HasUuids, InteractsWithMedia;
    protected $table = 'organisasi_dokumen';
    protected $keyType = 'string';

    protected function casts(): array
    {
        return [
            'jenis_dokumen' => JenisDokumen::class

        ];
    }

    public function organisasi(): BelongsTo
    {
        return $this->belongsTo(Organisasi::class);
    }

}
