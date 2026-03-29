<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreHeroSlideRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'kategori' => ['required', 'string', 'max:120'],
            'judul' => ['required', 'string', 'max:255'],
            'judul_sorot' => ['nullable', 'string', 'max:255'],
            'subtitle' => ['required', 'string', 'max:1000'],
            'urutan' => ['nullable', 'integer', 'min:0', 'max:65535'],
            'gambar' => ['required', 'image', 'max:5120'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'kategori.required' => 'Kategori wajib diisi.',
            'judul.required' => 'Judul wajib diisi.',
            'subtitle.required' => 'Subtitle wajib diisi.',
            'gambar.required' => 'Gambar hero wajib diunggah.',
            'gambar.image' => 'File harus berupa gambar.',
            'gambar.max' => 'Ukuran gambar maksimal 5 MB.',
        ];
    }
}
