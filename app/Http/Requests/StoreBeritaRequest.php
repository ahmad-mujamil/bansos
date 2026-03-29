<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBeritaRequest extends FormRequest
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
            'judul' => ['required', 'string', 'max:255'],
            'kategori_berita_id' => ['required', 'uuid', 'exists:kategori_berita,id'],
            'ringkasan' => ['required', 'string', 'max:500'],
            'konten' => ['required', 'string'],
            'gambar' => ['required', 'image', 'max:5120'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'judul.required' => 'Judul wajib diisi.',
            'kategori_berita_id.required' => 'Kategori wajib dipilih.',
            'kategori_berita_id.exists' => 'Kategori tidak valid.',
            'ringkasan.required' => 'Ringkasan wajib diisi.',
            'konten.required' => 'Konten wajib diisi.',
            'gambar.required' => 'Gambar utama wajib diunggah.',
            'gambar.image' => 'File harus berupa gambar.',
            'gambar.max' => 'Ukuran gambar maksimal 5 MB.',
        ];
    }
}
