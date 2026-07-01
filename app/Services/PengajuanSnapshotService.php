<?php

namespace App\Services;

use App\Enums\MomenSnapshot;
use App\Models\OrganisasiDetail;
use App\Models\Pengajuan;
use App\Models\PengajuanDetail;
use App\Models\PengajuanKelompokSnapshot;
use Illuminate\Support\Facades\Auth;

class PengajuanSnapshotService
{
    /**
     * Bekukan (snapshot) data pengajuan pada momen tertentu:
     *  - nama kelompok + daftar anggota (jika pengajuan kelompok/organisasi)
     *  - daftar penerima individu/keluarga (jika ada pengajuan_detail)
     *
     * Idempoten: memanggil ulang pada momen yang sama menimpa snapshot lama.
     * Aman dipanggil untuk semua jenis pengajuan.
     *
     * Panggil di dalam transaksi yang sudah berjalan pada controller.
     */
    public function capture(Pengajuan $pengajuan, MomenSnapshot $momen): void
    {
        $this->captureKelompok($pengajuan, $momen);
        $this->capturePenerima($pengajuan, $momen);
    }

    /**
     * Hapus semua snapshot (kelompok + penerima) pada momen tertentu.
     */
    public function forget(Pengajuan $pengajuan, MomenSnapshot $momen): void
    {
        $pengajuan->kelompokSnapshots()
            ->where('momen', $momen->value)
            ->get()
            ->each
            ->delete();

        $pengajuan->penerimaSnapshots()
            ->where('momen', $momen->value)
            ->delete();
    }

    private function captureKelompok(Pengajuan $pengajuan, MomenSnapshot $momen): ?PengajuanKelompokSnapshot
    {
        if (! $pengajuan->organisasi_id) {
            return null;
        }

        $organisasi = $pengajuan->organisasi()
            ->with('jenisKelompokMasyarakat', 'kecamatan', 'desa')
            ->first();

        if (! $organisasi) {
            return null;
        }

        $anggota = $organisasi->organisasiDetail()
            ->with('penduduk')
            ->orderBy('created_at')
            ->get();

        $snapshot = PengajuanKelompokSnapshot::updateOrCreate(
            [
                'pengajuan_id' => $pengajuan->id,
                'momen' => $momen->value,
            ],
            [
                'organisasi_id' => $organisasi->id,
                'nama_kelompok' => $organisasi->nama,
                'nomor' => $organisasi->nomor,
                'jenis' => $organisasi->jenis,
                'jenis_kelompok' => $organisasi->jenisKelompokMasyarakat?->nama,
                'tgl_pembentukan' => $organisasi->tgl_pembentukan,
                'wilayah_label' => $organisasi->wilayah_label,
                'jumlah_anggota' => $anggota->count(),
                'dibekukan_oleh' => Auth::id(),
            ]
        );

        // Bekukan ulang anggota dari awal supaya idempoten.
        $snapshot->anggota()->delete();

        foreach ($anggota as $detail) {
            /** @var OrganisasiDetail $detail */
            $snapshot->anggota()->create([
                'penduduk_id' => $detail->penduduk_id,
                'nik' => $detail->penduduk?->nik,
                'nama' => $detail->penduduk?->nama ?? '-',
                'alamat' => $detail->penduduk?->alamat,
                'jabatan' => $detail->jabatan?->value,
                'is_valid' => (bool) $detail->penduduk?->is_valid,
                'level_desil' => $detail->penduduk?->level_desil?->value,
            ]);
        }

        return $snapshot;
    }

    private function capturePenerima(Pengajuan $pengajuan, MomenSnapshot $momen): void
    {
        $details = $pengajuan->details()
            ->with('penduduk')
            ->orderBy('created_at')
            ->get();

        // Bekukan ulang dari awal supaya idempoten.
        $pengajuan->penerimaSnapshots()
            ->where('momen', $momen->value)
            ->delete();

        if ($details->isEmpty()) {
            return;
        }

        foreach ($details as $detail) {
            /** @var PengajuanDetail $detail */
            $pengajuan->penerimaSnapshots()->create([
                'momen' => $momen->value,
                'penduduk_id' => $detail->penduduk_id,
                'nik' => $detail->penduduk?->nik,
                'nama' => $detail->penduduk?->nama ?? '-',
                'alamat' => $detail->penduduk?->alamat,
                'nilai' => $detail->nilai,
                'is_valid' => (bool) $detail->penduduk?->is_valid,
                'level_desil' => $detail->penduduk?->level_desil?->value,
            ]);
        }
    }
}
