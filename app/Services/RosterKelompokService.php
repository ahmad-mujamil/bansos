<?php

namespace App\Services;

use App\Models\Organisasi;
use App\Models\OrganisasiDetail;
use App\Models\Scopes\TahunAnggaranScope;
use Illuminate\Support\Facades\DB;

class RosterKelompokService
{
    /**
     * Copy-on-first-use: bila roster kelompok untuk $tahun belum ada, salin dari
     * tahun terisi terdekat sebelumnya. Dipanggil saat roster pertama kali dibutuhkan
     * (buka kelola anggota / submit pengajuan). Aman dipanggil berulang (idempoten).
     */
    public function ensureSeeded(Organisasi $organisasi, int $tahun): void
    {
        // Tidak menulis ke tahun terkunci.
        if (tahun_terkunci($tahun)) {
            return;
        }

        $sudahAda = OrganisasiDetail::query()
            ->withoutGlobalScope(TahunAnggaranScope::class)
            ->where('organisasi_id', $organisasi->id)
            ->where('tahun_anggaran', $tahun)
            ->exists();

        if ($sudahAda) {
            return;
        }

        $tahunSumber = OrganisasiDetail::query()
            ->withoutGlobalScope(TahunAnggaranScope::class)
            ->where('organisasi_id', $organisasi->id)
            ->where('tahun_anggaran', '<', $tahun)
            ->max('tahun_anggaran');

        // Tidak ada tahun sumber → biarkan kosong (kelompok baru).
        if (! $tahunSumber) {
            return;
        }

        $sumber = OrganisasiDetail::query()
            ->withoutGlobalScope(TahunAnggaranScope::class)
            ->where('organisasi_id', $organisasi->id)
            ->where('tahun_anggaran', $tahunSumber)
            ->get();

        DB::transaction(function () use ($sumber, $tahun) {
            foreach ($sumber as $row) {
                OrganisasiDetail::query()->create([
                    'organisasi_id' => $row->organisasi_id,
                    'penduduk_id' => $row->penduduk_id,
                    'jabatan' => $row->jabatan?->value,
                    'tahun_anggaran' => $tahun,
                ]);
            }
        });
    }
}
