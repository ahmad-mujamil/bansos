<?php

namespace App\Livewire\Reports;

use App\Enums\JenisPengajuan;
use App\Livewire\DataList;
use App\Models\Kecamatan;
use App\Models\Penduduk;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;

/**
 * Detail metrik "Verifikasi NIK" pada dashboard: daftar penduduk yang NIK-nya
 * belum pernah diverifikasi Dukcapil, lengkap dengan kelompok tempat mereka
 * terdaftar (bila ada).
 *
 * Cakupannya mengikuti kartu dashboard — anggota organisasi/kelompok yang
 * jenisnya sesuai jenis pengajuan, termasuk yang belum pernah mengajukan.
 */
class PendudukBelumVerifikasiList extends DataList
{
    public string $jenis = '';

    public string $kecamatanId = 'all';

    public function mount(string $jenis = ''): void
    {
        $this->configure([
            'model' => Penduduk::class,
            'columns' => $this->defaultColumns(),
            'with' => ['desa', 'kecamatan', 'organisasiDetails.organisasi'],
            'defaultSortColumn' => 'nama',
            'defaultSortDirection' => 'asc',
            'perPage' => 10,
            'searchPlaceholder' => 'Cari nama / NIK…',
            'enableDetail' => false,
            'showSearch' => true,
        ]);

        $this->jenis = $jenis;
    }

    public function updatedKecamatanId(): void
    {
        $this->resetPage();
    }

    protected function applyExtraQuery(Builder $query): void
    {
        $query->whereNull('validated_at');

        $jenis = JenisPengajuan::tryFrom($this->jenis);
        if ($jenis !== null) {
            $jenisOrganisasi = array_map(fn ($j) => $j->value, $jenis->getJenisOrganisasi());
            $query->whereHas(
                'organisasiDetails.organisasi',
                fn (Builder $q) => $q->whereIn('jenis', $jenisOrganisasi)
            );
        }

        if ($this->kecamatanId !== 'all') {
            $query->where('kecamatan_id', $this->kecamatanId);
        }
    }

    private function defaultColumns(): array
    {
        return [
            ['key' => 'nik', 'label' => 'NIK', 'searchable' => true, 'sortable' => true],
            ['key' => 'nama', 'label' => 'Nama', 'searchable' => true, 'sortable' => true],
            ['key' => 'kelompok_label', 'label' => 'Kelompok', 'format' => 'raw'],
            ['key' => 'wilayah_label', 'label' => 'Kecamatan / Desa'],
        ];
    }

    public function render(): View
    {
        $items = $this->buildQuery()->paginate($this->perPage);

        return view('livewire.reports.penduduk-belum-verifikasi-list', [
            'items' => $items,
            'kecamatanOptions' => Kecamatan::query()->orderBy('nama')->get(['id', 'nama']),
        ]);
    }
}
