<?php

namespace App\Http\Requests;

use App\Models\TahunAnggaran;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TahunAnggaranRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_terkunci' => $this->boolean('is_terkunci'),
        ]);
    }

    public function rules(): array
    {
        $ta = $this->route('tahun_anggaran');
        $id = $ta instanceof TahunAnggaran ? $ta->id : $ta;

        return [
            'tahun' => ['required', 'integer', 'min:2000', 'max:2100', Rule::unique('tahun_anggaran', 'tahun')->ignore($id)],
            'label' => ['nullable', 'string', 'max:100'],
            'keterangan' => ['nullable', 'string', 'max:255'],
            'is_terkunci' => ['boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'tahun.unique' => 'Tahun tersebut sudah terdaftar.',
        ];
    }
}
