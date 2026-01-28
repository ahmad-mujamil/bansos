<?php

namespace App\Http\Requests;

use App\Enums\JenisOrganisasi;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class KelompokMasyarakatRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        $organisasiId = $this->route('kelompok_masyarakat') ?? null;
        $jenis = $this->input('jenis');

        return [
            'nama' => ['required', 'string', 'max:255'],
            'jenis' => ['required', Rule::enum(JenisOrganisasi::class)],
            'nomor' => [
                'required',
                'string',
                'max:100',
                Rule::unique('organisasi', 'nomor')->where('jenis', $jenis)->ignore($organisasiId),
            ],
            'tgl_pembentukan' => ['required', 'date'],
            'kecamatan_id' => ['required', 'exists:kecamatan,id'],
            'desa_id' => ['required', 'exists:desa,id'],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }
}
