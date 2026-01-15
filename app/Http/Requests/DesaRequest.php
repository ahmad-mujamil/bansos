<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DesaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        $desaId = $this->route('desa')?->id ?? null;
        $kecamatanId = $this->input('kecamatan_id');

        return [
            "nama" => ["required", "string", "max:255"],
            "kecamatan_id" => ["required", "exists:kecamatan,id"],
        ];
    }
}

