<?php

namespace App\Models;

use App\Enums\JenisOrganisasi;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

class Organisasi extends Model
{
    use HasUuids;
    protected $table = 'organisasi';
    protected $keyType = 'string';

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'tgl_pembentukan' => 'date:Y-m-d',
            'is_active' => 'boolean',
            'is_blacklist' => 'boolean'
        ];
    }

    public function organisasiDetail(): HasMany
    {
        return $this->hasMany(OrganisasiDetail::class);
    }

    public function organisasiDokumen(): HasMany
    {
        return $this->hasMany(OrganisasiDokumen::class);
    }

    public function blacklistLogs(): HasMany
    {
        return $this->hasMany(LogBlacklistOrganisasi::class);
    }

    public function kecamatan(): BelongsTo
    {
        return $this->belongsTo(Kecamatan::class);
    }
    public function desa(): BelongsTo
    {
        return $this->belongsTo(Desa::class);
    }

    public function opd(): BelongsTo
    {
        return $this->belongsTo(Opd::class);
    }

    /**
     * Anggota yang terhubung ke penduduk dengan is_valid = false (nama, NIK, label status).
     *
     * @return Collection<int, array{nama: string, nik: string, status: string}>
     */
    public function anggotaBelumTerverifikasiData(): Collection
    {
        return $this->organisasiDetail()
            ->with('penduduk')
            ->whereHas('penduduk', fn ($q) => $q->where('is_valid', false))
            ->orderBy('created_at')
            ->get()
            ->map(fn (OrganisasiDetail $d) => [
                'nama' => $d->penduduk?->nama ?? '-',
                'nik' => $d->penduduk?->nik ?? '-',
                'status' => $d->penduduk?->labelStatusVerifikasi() ?? '-',
            ]);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
