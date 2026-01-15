<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class KecamatanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        $kecamatanId = $this->route('kecamatan')?->id ?? null;

        return [
            "nama" => ["required", "string", "max:255", Rule::unique('kecamatan', 'nama')->ignore($kecamatanId)],
        ];
    }
}

