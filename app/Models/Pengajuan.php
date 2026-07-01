<?php

namespace App\Models;

use App\Enums\JenisPenerimaBantuan;
use App\Enums\JenisPengajuan;
use App\Enums\MomenSnapshot;
use App\Enums\PengajuanStatus;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Pengajuan extends Model implements HasMedia
{
    use HasUuids, InteractsWithMedia;

    /**
     * @var mixed|true
     */
    protected $table = 'pengajuan';

    protected $keyType = 'string';

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'status' => PengajuanStatus::class,
            'jenis_penerima_bantuan' => JenisPenerimaBantuan::class,
            'kategori_pengajuan' => JenisPengajuan::class,
            'verified_at' => 'datetime',
        ];
    }

    public function jenisBantuan(): BelongsTo
    {
        return $this->belongsTo(JenisBantuan::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function organisasi(): BelongsTo
    {
        return $this->belongsTo(Organisasi::class);
    }

    public function opd(): BelongsTo
    {
        return $this->belongsTo(Opd::class);
    }

    public function desa(): BelongsTo
    {
        return $this->belongsTo(Desa::class);
    }

    public function verifiedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    // public function details(): HasMany
    // {
    //     return $this->hasMany(PengajuanDetail::class);
    // }
    public function details(): HasMany
    {
        return $this->hasMany(PengajuanDetail::class);
    }

    public function logs(): HasMany
    {
        return $this->hasMany(PengajuanLog::class);
    }

    public function kelompokSnapshots(): HasMany
    {
        return $this->hasMany(PengajuanKelompokSnapshot::class);
    }

    public function penerimaSnapshots(): HasMany
    {
        return $this->hasMany(PengajuanPenerimaSnapshot::class);
    }

    /**
     * Snapshot kelompok pada momen tertentu (diajukan | disetujui).
     */
    public function snapshotMomen(MomenSnapshot $momen): ?PengajuanKelompokSnapshot
    {
        return $this->kelompokSnapshots()
            ->with('anggota')
            ->where('momen', $momen->value)
            ->first();
    }

    /**
     * Snapshot daftar penerima individu/keluarga pada momen tertentu.
     *
     * @return \Illuminate\Support\Collection<int, PengajuanPenerimaSnapshot>
     */
    public function penerimaMomen(MomenSnapshot $momen): \Illuminate\Support\Collection
    {
        return $this->penerimaSnapshots()
            ->where('momen', $momen->value)
            ->orderBy('created_at')
            ->get();
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('pengajuan')
            ->singleFile()
            ->acceptsMimeTypes(['application/pdf']);
    }

    public function canEdit(): bool
    {
        return $this->status === PengajuanStatus::DRAFT;
    }

    public function canSubmit(): bool
    {
        return $this->status === PengajuanStatus::DRAFT;
    }

    public function canDelete(): bool
    {
        if (in_array($this->status, [PengajuanStatus::DISETUJUI, PengajuanStatus::DITOLAK], true)) {
            return false;
        }

        return ! $this->verifikasiPengajuan()->exists();
    }

    public function pemeriksa(): HasMany
    {
        return $this->hasMany(PengajuanPemeriksa::class);
    }

    public function verifikasiPengajuan(): HasOne
    {
        return $this->hasOne(VerifikasiPengajuan::class);
    }

    public function bast(): HasOne
    {
        return $this->hasOne(Bast::class);
    }

    public function realisasi(): HasOne
    {
        return $this->hasOne(PengajuanRealisasi::class);
    }
}
