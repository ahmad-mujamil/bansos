<?php

namespace App\Livewire\Reports;

use App\Enums\JenisPengajuan;
use App\Livewire\DataList;
use App\Models\Kecamatan;
use App\Models\Organisasi;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;

/**
 * Daftar organisasi teregistrasi (dipakai oleh metrik "Teregistrasi" dashboard).
 *
 * Filter mengikuti data kelompok (pencarian, status, kecamatan) dan tiap baris
 * dapat di-expand untuk melihat anggota seperti pada detail pengajuan.
 */
class OrganisasiTeregistrasiList extends DataList
{
    public string $jenis = '';
    public string $status = 'all';
    public string $kecamatanId = 'all';
    public ?string $expandedId = null;

    public function mount(string $jenis = ''): void
    {
        $this->configure([
            'model' => Organisasi::class,
            'columns' => $this->defaultColumns(),
            'with' => ['kecamatan', 'desa', 'opd', 'organisasiDetail.penduduk'],
            'defaultSortColumn' => 'nama',
            'defaultSortDirection' => 'asc',
            'perPage' => 10,
            'searchPlaceholder' => 'Cari nama / nomor SK…',
            'enableDetail' => false,
            'showSearch' => true,
        ]);

        $this->jenis = $jenis;
    }

    public function updatedStatus(): void
    {
        if (! in_array($this->status, ['aktif', 'nonaktif', 'blacklist'], true)) {
            $this->status = 'all';
        }
        $this->expandedId = null;
        $this->resetPage();
    }

    public function updatedKecamatanId(): void
    {
        $this->expandedId = null;
        $this->resetPage();
    }

    public function toggleAnggota(string $id): void
    {
        $this->expandedId = $this->expandedId === $id ? null : $id;
    }

    protected function applyExtraQuery(Builder $query): void
    {
        $jenis = JenisPengajuan::tryFrom($this->jenis);
        if ($jenis !== null) {
            $jenisOrganisasi = array_map(fn ($j) => $j->value, $jenis->getJenisOrganisasi());
            $query->whereIn('jenis', $jenisOrganisasi);
        }

        if ($this->status === 'aktif') {
            $query->where('is_active', true)->where('is_blacklist', false);
        } elseif ($this->status === 'nonaktif') {
            $query->where('is_active', false);
        } elseif ($this->status === 'blacklist') {
            $query->where('is_blacklist', true);
        }

        if ($this->kecamatanId !== 'all') {
            $query->where('kecamatan_id', $this->kecamatanId);
        }

        $query->withCount('organisasiDetail');
    }

    private function defaultColumns(): array
    {
        return [
            ['key' => 'nama', 'label' => 'Nama', 'searchable' => true, 'sortable' => true],
            ['key' => 'nomor', 'label' => 'Nomor SK / Tgl', 'searchable' => true, 'sub_key' => 'tgl_pembentukan', 'sub_format' => 'date'],
            ['key' => 'wilayah_label', 'label' => 'Kecamatan / Desa'],
            ['key' => 'opd.nama', 'label' => 'OPD'],
            ['key' => 'organisasi_detail_count', 'label' => 'Anggota'],
            ['key' => 'status_badge', 'label' => 'Status', 'format' => 'raw'],
        ];
    }

    public function render(): View
    {
        $items = $this->buildQuery()->paginate($this->perPage);

        return view('livewire.reports.organisasi-teregistrasi-list', [
            'items' => $items,
            'kecamatanOptions' => Kecamatan::query()->orderBy('nama')->get(['id', 'nama']),
        ]);
    }
}
