<?php

namespace App\Livewire;

use Livewire\Component;
use App\Enums\Kecamatan;

class WilayahSelect extends Component
{
    public ?string $kecamatan = null;
    public ?string $desa = null;

    public array $desaOptions = [];

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
    }

    private function syncDesaOptions(): void
    {
        if (!$this->kecamatan) {
            $this->desaOptions = [];
            return;
        }

        // aman: dari string -> enum
        try {
            $this->desaOptions = Kecamatan::from($this->kecamatan)->desaKelurahan();
        } catch (\ValueError) {
            $this->desaOptions = [];
        }
    }

    public function render()
    {
        return view('livewire.wilayah-select', [
            'kecamatanOptions' => Kecamatan::options(),
        ]);
    }
}
