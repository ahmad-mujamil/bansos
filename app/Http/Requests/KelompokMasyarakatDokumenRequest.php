<?php

namespace App\Http\Requests;

use App\Enums\JenisDokumen;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class KelompokMasyarakatDokumenRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        $isUpdate = $this->route('dokuman') ?? $this->route('dokumen');

        return [
            'jenis_dokumen' => ['required', Rule::enum(JenisDokumen::class)],
            'keterangan' => ['required', 'string', 'max:255'],
            'file' => [$isUpdate ? 'nullable' : 'required', 'file', 'mimes:pdf,jpeg,jpg,png,webp', 'max:10240'],
        ];
    }
}
