<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Kecamatan;
use App\Models\Desa;

class WilayahSelect extends Component
{
    public ?string $kecamatan = null;
    public ?string $desa = null;

    public $desaOptions = [];

    public function mount(?string $kecamatan = null, ?string $desa = null): void
    {
        $this->kecamatan = $kecamatan;
        $this->desa = $desa;

        $this->syncDesaOptions();
    }

    public function updatedKecamatan($value): void
    {
        $this->desa = null;
        $this->syncDesaOptions();
        $this->dispatch('set-kecamatan', kecamatan: $this->kecamatan);
        $this->dispatch('resync-desa', desaOptions: $this->desaOptions, desa: $this->desa);
    }

    public function updatedDesa($value): void
    {
        $this->dispatch('set-desa', desa: $value);
    }

    private function syncDesaOptions(): void
    {
        if (!$this->kecamatan) {
            $this->desaOptions = [];
            return;
        }

        $this->desaOptions = Desa::query()->where('kecamatan_id', $this->kecamatan)
            ->orderBy('nama')
            ->get(['id', 'nama'])
            ->toArray();
    }

    public function render()
    {
        return view('livewire.wilayah-select', [
            'kecamatanOptions' => Kecamatan::query()
                ->orderBy('nama')
                ->get(['id', 'nama']),
        ]);
    }
}
