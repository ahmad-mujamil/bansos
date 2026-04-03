<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OpdRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'nama' => ['required', 'string', 'max:255'],
            'alamat' => ['required', 'string', 'max:500'],
            'no_telp' => ['required', 'string', 'max:20'],
            'email' => ['required', 'email', 'max:255'],
            'website' => ['nullable', 'string', 'max:255'],
            'fax' => ['nullable', 'string', 'max:20'],
            'kepala_opd' => ['required', 'string', 'max:255'],
            'nip' => ['required', 'string'],
        ];
    }
}
