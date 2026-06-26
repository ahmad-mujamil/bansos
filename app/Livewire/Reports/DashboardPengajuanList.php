<?php

namespace App\Livewire\Reports;

use App\Enums\JenisPengajuan;
use App\Enums\JenisPenerimaBantuan;
use App\Enums\PengajuanStatus;
use App\Models\Opd;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;

/**
 * Daftar pengajuan untuk halaman detail dashboard.
 *
 * Sama seperti laporan pengajuan (filter kategori, status, pencarian,
 * detail) namun dengan filter tambahan penerima (perorangan/organisasi)
 * dan verifikasi (usulan/sudah verifikasi BA) sesuai metrik dashboard
 * yang diklik.
 */
class DashboardPengajuanList extends LaporanPengajuanList
{
    public string $penerima = 'all';
    public string $verif = 'all';

    public function mount(string $kategori = 'all', string $status = 'all', string $penerima = 'all', string $verif = 'all', string $opd = 'all'): void
    {
        parent::mount();

        $this->kategori = JenisPengajuan::tryFrom($kategori) !== null ? $kategori : 'all';
        $this->status = PengajuanStatus::tryFrom($status) !== null ? $status : 'all';
        $this->penerima = in_array($penerima, ['perorangan', 'organisasi'], true) ? $penerima : 'all';
        $this->verif = in_array($verif, ['usulan', 'verifikasi'], true) ? $verif : 'all';
        $this->opd = $opd !== '' ? $opd : 'all';
    }

    public function updatedPenerima(): void
    {
        if (! in_array($this->penerima, ['perorangan', 'organisasi'], true)) {
            $this->penerima = 'all';
        }
        $this->expandedId = null;
        $this->resetPage();
    }

    public function updatedVerif(): void
    {
        if (! in_array($this->verif, ['usulan', 'verifikasi'], true)) {
            $this->verif = 'all';
        }
        $this->expandedId = null;
        $this->resetPage();
    }

    protected function applyExtraQuery(Builder $query): void
    {
        parent::applyExtraQuery($query);

        $jenisPerorangan = [JenisPenerimaBantuan::INDIVIDU->value, JenisPenerimaBantuan::KELUARGA->value];

        if ($this->penerima === 'perorangan') {
            $query->whereIn('jenis_penerima_bantuan', $jenisPerorangan);
        } elseif ($this->penerima === 'organisasi') {
            $query->whereNotIn('jenis_penerima_bantuan', $jenisPerorangan);
        }

        if ($this->verif === 'verifikasi') {
            $query->whereHas('verifikasiPengajuan.media', fn (Builder $q) => $q->where('collection_name', 'ba-verifikasi'));
        } elseif ($this->verif === 'usulan') {
            $query->whereDoesntHave('verifikasiPengajuan.media', fn (Builder $q) => $q->where('collection_name', 'ba-verifikasi'));
        }
    }

    public function render(): View
    {
        $items = $this->buildQuery()->paginate($this->perPage);

        return view('livewire.reports.dashboard-pengajuan-list', [
            'items' => $items,
            'kategoriOptions' => [
                JenisPengajuan::BANSOS,
                JenisPengajuan::HIBAH,
                JenisPengajuan::BANTUAN_KELOMPOK,
            ],
            'statusOptions' => PengajuanStatus::cases(),
            'opdOptions' => Opd::query()->orderBy('nama')->get(['id', 'nama']),
        ]);
    }
}
