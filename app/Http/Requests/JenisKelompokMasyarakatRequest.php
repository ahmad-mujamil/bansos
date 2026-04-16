<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class JenisKelompokMasyarakatRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }

    public function rules(): array
    {
        $jenisKelompokMasyarakatId = $this->route('jenis_kelompok_masyarakat')?->id ?? null;

        return [
            'nama' => [
                'required',
                'string',
                'max:255',
                Rule::unique('jenis_kelompok_masyarakat', 'nama')->ignore($jenisKelompokMasyarakatId),
            ],
            'keterangan' => ['nullable', 'string', 'max:255'],
        ];
    }
}
