<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class TahunAnggaranScope implements Scope
{
    /**
     * Hanya membatasi query pada request web (konteks tahun sudah di-bind middleware).
     * Di console/queue konteks tidak di-bind → tidak difilter (semua tahun terlihat),
     * agar command backfill/laporan lintas tahun tetap berfungsi.
     */
    public function apply(Builder $builder, Model $model): void
    {
        if (! app()->bound('tahun_anggaran_terpilih')) {
            return;
        }

        $builder->where($model->getTable().'.tahun_anggaran', tahun_aktif());
    }
}
