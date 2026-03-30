<?php

namespace App\Http\Requests;

use App\Enums\KategoriBantuan;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpsertAlurBantuanRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'alur' => ['required', 'array', 'size:3'],
            'alur.*.kategori' => ['required', 'string', Rule::in(array_map(fn (KategoriBantuan $kategori) => $kategori->value, KategoriBantuan::cases()))],
            'alur.*.steps' => ['required', 'array', 'min:1'],
            'alur.*.steps.*.judul' => ['required', 'string', 'max:120'],
            'alur.*.steps.*.deskripsi' => ['required', 'string', 'max:500'],
        ];
    }

    public function messages(): array
    {
        return [
            'alur.required' => 'Data alur bantuan wajib diisi.',
            'alur.size' => 'Semua kategori alur bantuan wajib diisi.',
            'alur.*.kategori.in' => 'Kategori alur bantuan tidak valid.',
        ];
    }
}
