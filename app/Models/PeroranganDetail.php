<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class PeroranganDetail extends Model implements HasMedia
{
    use HasFactory, HasUuids, InteractsWithMedia;
    
    protected $table = 'perorangan_detail';
    protected $guarded = [];
    
    const COLLECTION_KTP = 'ktp';
    const COLLECTION_KK = 'kk';
    const COLLECTION_FOTO_RUMAH_USAHA = 'foto_rumah_usaha';
    const COLLECTION_SURAT_KETERANGAN_TIDAK_MAMPU = 'surat_keterangan_tidak_mampu';

    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function desa() : BelongsTo
    {
        return $this->belongsTo(Desa::class, 'desa_id', 'id');
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection(self::COLLECTION_KTP)
            ->singleFile()
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'application/pdf']);

        $this->addMediaCollection(self::COLLECTION_KK)
            ->singleFile()
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'application/pdf']);

        $this->addMediaCollection(self::COLLECTION_FOTO_RUMAH_USAHA)
            ->singleFile()
            ->acceptsMimeTypes(['image/jpeg', 'image/png']);

        $this->addMediaCollection(self::COLLECTION_SURAT_KETERANGAN_TIDAK_MAMPU)
            ->singleFile()
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'application/pdf']);
    }
}
