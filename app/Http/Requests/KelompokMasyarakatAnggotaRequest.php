<?php

namespace App\Http\Requests;

use App\Enums\JabatanOrganisasi;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class KelompokMasyarakatAnggotaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        $organisasiId = $this->route('kelompok_masyarakat');
        $anggotaId = $this->route('anggota');

        return [
            'penduduk_id' => [
                'required',
                'exists:penduduk,id',
                Rule::unique('organisasi_detail', 'penduduk_id')
                    ->where('organisasi_id', $organisasiId)
                    ->ignore($anggotaId),
            ],
            'jabatan' => ['required', Rule::enum(JabatanOrganisasi::class)],
        ];
    }

    public function messages(): array
    {
        return [
            'penduduk_id.unique' => 'Penduduk tersebut sudah tercatat sebagai anggota organisasi ini.',
        ];
    }
}
