<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfilKantorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    protected function prepareForValidation(): void
    {
        $merge = [];
        foreach (['email', 'website', 'nama_instansi', 'kepala_dinas', 'nip_kepala_dinas', 'sekdis', 'nip_sekdis', 'lokasi_kantor', 'no_telepon'] as $key) {
            if ($this->has($key) && $this->input($key) === '') {
                $merge[$key] = null;
            }
        }
        if ($merge !== []) {
            $this->merge($merge);
        }
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'nama_instansi' => ['nullable', 'string', 'max:255'],
            'kepala_dinas' => ['nullable', 'string', 'max:255'],
            'nip_kepala_dinas' => ['nullable', 'string', 'max:30'],
            'sekdis' => ['nullable', 'string', 'max:255'],
            'nip_sekdis' => ['nullable', 'string', 'max:30'],
            'lokasi_kantor' => ['nullable', 'string', 'max:2000'],
            'no_telepon' => ['nullable', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:255'],
            'website' => ['nullable', 'url', 'max:500'],
            'foto_kepala_dinas' => ['nullable', 'image', 'max:5120'],
            'foto_sekdis' => ['nullable', 'image', 'max:5120'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'email.email' => 'Format email tidak valid.',
            'website.url' => 'Format URL website tidak valid.',
            'foto_kepala_dinas.image' => 'Foto kepala dinas harus berupa gambar.',
            'foto_kepala_dinas.max' => 'Ukuran foto kepala dinas maksimal 5 MB.',
            'foto_sekdis.image' => 'Foto sekretaris harus berupa gambar.',
            'foto_sekdis.max' => 'Ukuran foto sekretaris maksimal 5 MB.',
        ];
    }
}
