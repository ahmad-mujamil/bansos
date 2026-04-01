<?php

namespace App\Models;

use App\Enums\RupaBantuan;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class VerifikasiPengajuan extends Model implements HasMedia
{
    use HasUuids, InteractsWithMedia;

    protected $table = 'verifikasi_pengajuan';

    protected $keyType = 'string';

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'lulus_kriteria'    => 'boolean',
            'lulus_administrasi' => 'boolean',
            'lulus_kesesuaian'  => 'boolean',
            'sesuai_program_pemda' => 'boolean',
            'rupa_bantuan'      => RupaBantuan::class,
            'tgl_disahkan'      => 'date',
        ];
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('ba-verifikasi')
            ->singleFile()
            ->acceptsMimeTypes(['application/pdf']);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function pengajuan(): BelongsTo
    {
        return $this->belongsTo(Pengajuan::class);
    }
}
