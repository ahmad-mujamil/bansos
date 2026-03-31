<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class ProfilKantor extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $table = 'profil_kantor';

    protected $fillable = [
        'nama_instansi',
        'kepala_dinas',
        'nip_kepala_dinas',
        'sekdis',
        'nip_sekdis',
        'lokasi_kantor',
        'no_telepon',
        'email',
        'website',
    ];

    public static function instance(): self
    {
        $existing = static::query()->first();

        if ($existing instanceof self) {
            return $existing;
        }

        return static::query()->create([]);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('foto_kepala_dinas')
            ->singleFile()
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/webp', 'image/gif']);

        $this->addMediaCollection('foto_sekdis')
            ->singleFile()
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/webp', 'image/gif']);
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->fit(Fit::Crop, 240, 300)
            ->nonQueued();
    }
}
