<?php

namespace App\Models;

use App\Enums\RupaBantuan;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VerifikasiPengajuan extends Model
{
    use HasUuids;

    protected $table = 'verifikasi_pengajuan';

    protected $keyType = 'string';

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'lulus_kriteria' => 'boolean',
            'lulus_administrasi' => 'boolean',
            'lulus_kesesuaian' => 'boolean',
            'sesuai_program_pemda' => 'boolean',
            'rupa_bantuan' => RupaBantuan::class,

        ];
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
