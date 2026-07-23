<?php

namespace App\Models\Concerns;

use App\Models\Scopes\TahunAnggaranScope;

/**
 * Model yang datanya dipisah per tahun anggaran.
 *
 * - Global scope: query web otomatis difilter ke tahun terpilih.
 * - Auto-stamp: kolom tahun_anggaran diisi tahun aktif saat create bila kosong.
 * - Kunci: menulis/menghapus data pada tahun terkunci ditolak (hanya di web).
 */
trait BelongsToTahunAnggaran
{
    public static function bootBelongsToTahunAnggaran(): void
    {
        static::addGlobalScope(new TahunAnggaranScope);

        static::creating(function ($model) {
            if (empty($model->tahun_anggaran)) {
                $model->tahun_anggaran = tahun_aktif();
            }
        });

        static::saving(function ($model) {
            static::guardTahunTerkunci((int) $model->tahun_anggaran);
        });

        static::deleting(function ($model) {
            static::guardTahunTerkunci((int) $model->tahun_anggaran);
        });
    }

    protected static function guardTahunTerkunci(int $tahun): void
    {
        // Hanya tegakkan di konteks web; console (backfill/seed) dibebaskan.
        if (! app()->bound('tahun_anggaran_terpilih')) {
            return;
        }

        if ($tahun && tahun_terkunci($tahun)) {
            abort(403, "Tahun anggaran {$tahun} terkunci (read-only). Data tidak dapat diubah.");
        }
    }

    /**
     * Bypass filter tahun untuk laporan/dashboard lintas tahun.
     */
    public function scopeSemuaTahun($query)
    {
        return $query->withoutGlobalScope(TahunAnggaranScope::class);
    }
}
