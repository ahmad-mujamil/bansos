<?php

namespace App\Livewire\Kelola;

use App\Enums\KategoriBantuan;
use App\Models\AlurBantuan;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class AlurBantuanForm extends Component
{
    /**
     * @var array<int, array{
     *   kategori: string,
     *   label: string,
     *   steps: array<int, array{judul: string, deskripsi: string, icon: string}>
     * }>
     */
    public array $alur = [];

    public function mount(): void
    {
        $existing = AlurBantuan::query()
            ->with('steps')
            ->get()
            ->keyBy(fn (AlurBantuan $alurBantuan) => $alurBantuan->kategori?->value ?? '');

        $this->alur = collect(KategoriBantuan::cases())
            ->map(function (KategoriBantuan $kategori) use ($existing): array {
                /** @var AlurBantuan|null $alurBantuan */
                $alurBantuan = $existing->get($kategori->value);
                $steps = $alurBantuan?->steps?->map(fn ($step): array => [
                    'judul' => $step->judul,
                    'deskripsi' => $step->deskripsi,
                    'icon' => (string) ($step->icon ?? ''),
                ])->values()->all() ?? [];

                if ($steps === []) {
                    $steps = $this->defaultSteps();
                }

                return [
                    'kategori' => $kategori->value,
                    'label' => $kategori->getDescription(),
                    'steps' => $steps,
                ];
            })
            ->values()
            ->all();
    }

    /**
     * @return array<int, array{judul: string, deskripsi: string, icon: string}>
     */
    private function defaultSteps(): array
    {
        return [
            ['judul' => '1. Pendataan', 'deskripsi' => 'Petugas melakukan verifikasi data lapangan di lingkungan RT/RW setempat.', 'icon' => 'person_search'],
            ['judul' => '2. Verifikasi', 'deskripsi' => 'Validasi data dilakukan oleh perangkat daerah terkait.', 'icon' => 'fact_check'],
            ['judul' => '3. Penetapan', 'deskripsi' => 'Hasil verifikasi ditetapkan sebagai dasar pemberian bantuan.', 'icon' => 'gavel'],
            ['judul' => '4. Penyaluran', 'deskripsi' => 'Bantuan disalurkan kepada penerima sesuai ketentuan yang berlaku.', 'icon' => 'payments'],
        ];
    }

    public function rules(): array
    {
        return [
            'alur' => ['required', 'array', 'size:3'],
            'alur.*.kategori' => ['required', 'string'],
            'alur.*.steps' => ['required', 'array', 'min:1'],
            'alur.*.steps.*.judul' => ['required', 'string', 'max:120'],
            'alur.*.steps.*.deskripsi' => ['required', 'string', 'max:500'],
            'alur.*.steps.*.icon' => ['nullable', 'string', 'max:80'],
        ];
    }

    public function messages(): array
    {
        return [
            'alur.*.steps.min' => 'Setiap kategori harus memiliki minimal 1 step.',
        ];
    }

    public function addStep(int $kategoriIndex): void
    {
        if (! array_key_exists($kategoriIndex, $this->alur)) {
            return;
        }

        $nextNumber = count($this->alur[$kategoriIndex]['steps']) + 1;
        $this->alur[$kategoriIndex]['steps'][] = [
            'judul' => $nextNumber.'. Step baru',
            'deskripsi' => '',
            'icon' => '',
        ];
    }

    public function removeStep(int $kategoriIndex, int $stepIndex): void
    {
        if (! isset($this->alur[$kategoriIndex]['steps'][$stepIndex])) {
            return;
        }

        if (count($this->alur[$kategoriIndex]['steps']) <= 1) {
            return;
        }

        unset($this->alur[$kategoriIndex]['steps'][$stepIndex]);
        $this->alur[$kategoriIndex]['steps'] = array_values($this->alur[$kategoriIndex]['steps']);
    }

    public function save(): void
    {
        $validated = $this->validate();

        DB::beginTransaction();
        try {
            foreach ($validated['alur'] as $item) {
                $alurBantuan = AlurBantuan::query()->updateOrCreate(
                    ['kategori' => $item['kategori']],
                    [],
                );

                $alurBantuan->steps()->delete();
                $alurBantuan->steps()->createMany(
                    collect($item['steps'])
                        ->values()
                        ->map(fn (array $step, int $index): array => [
                            'urutan' => $index + 1,
                            'judul' => $step['judul'],
                            'deskripsi' => $step['deskripsi'],
                            'icon' => ($step['icon'] ?? '') !== '' ? $step['icon'] : null,
                        ])
                        ->all()
                );
            }

            DB::commit();
            $this->dispatch(
                'app-swal',
                icon: 'success',
                title: 'Berhasil',
                text: 'Alur bantuan berhasil disimpan.',
                timer: 2500,
            );
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->dispatch(
                'app-swal',
                icon: 'error',
                title: 'Gagal',
                text: $th->getMessage(),
                timer: 3500,
            );
        }
    }

    public function render()
    {
        return view('livewire.kelola.alur-bantuan-form');
    }
}
