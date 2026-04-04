<?php

namespace App\Livewire;

use App\Models\Penduduk;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Livewire\Component;

class CariPendudukByNik extends Component
{
    public string $nik = '';

    public ?Penduduk $penduduk = null;

    public bool $hasSearched = false;

    public function search(): void
    {
        $this->nik = preg_replace('/\D/', '', $this->nik);
        $this->penduduk = null;
        $this->hasSearched = false;

        $this->validate([
            'nik' => ['required', 'regex:/^[0-9]{16}$/'],
        ], [
            'nik.required' => 'NIK wajib diisi.',
            'nik.regex' => 'NIK harus tepat 16 digit angka.',
        ]);

        $this->hasSearched = true;

        $this->penduduk = Penduduk::query()
            ->where('nik', $this->nik)
            ->with(['desa.kecamatan', 'kecamatan'])
            ->first();
    }

    /**
     * @return Collection<int, \App\Models\OrganisasiDetail>
     */
    public function keanggotaanKelompok(): Collection
    {
        if ($this->penduduk === null) {
            return collect();
        }

        $query = $this->penduduk->organisasiDetails()
            ->with(['organisasi.kecamatan', 'organisasi.desa', 'organisasi.opd']);

        $user = auth()->user();
        if ($user !== null && $user->is_opd() && $user->opd_id !== null) {
            $query->whereHas('organisasi', function ($q) use ($user): void {
                $q->where('opd_id', $user->opd_id);
            });
        }

        return $query->get();
    }

    public function render(): View
    {
        return view('livewire.cari-penduduk-by-nik', [
            'keanggotaan' => $this->keanggotaanKelompok(),
        ]);
    }
}
