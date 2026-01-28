<?php

namespace App\Models;

use App\Enums\JenisDokumen;
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

    protected $fillable = ['organisasi_id', 'keterangan', 'jenis_dokumen'];

    protected function casts(): array
    {
        return [
            'jenis_dokumen' => JenisDokumen::class,
        ];
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('dokumen')
            ->singleFile()
            ->acceptsMimeTypes(['application/pdf', 'image/jpeg', 'image/png', 'image/webp']);
    }

    public function organisasi(): BelongsTo
    {
        return $this->belongsTo(Organisasi::class);
    }
}
