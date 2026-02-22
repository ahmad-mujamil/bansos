<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Enums\JenisKelamin;
use App\Enums\StatusPerkawinan;
use App\Enums\LevelDesil;

class PendudukRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        $pendudukId = $this->route('penduduk')?->id ?? null;

        return [
            'nik' => ['required', 'string', 'max:25', Rule::unique('penduduk', 'nik')->ignore($pendudukId)],
            'no_kk' => ['required', 'string', 'max:25'],
            'nama' => ['required', 'string', 'max:255'],
            'alamat' => ['required', 'string', 'max:255'],
            'tempat_lahir' => ['required', 'string', 'max:255'],
            'tgl_lahir' => ['required', 'date'],
            'jk' => ['required', Rule::enum(JenisKelamin::class)],
            'agama' => ['required', 'string', 'max:50'],
            'status_perkawinan' => ['required', Rule::enum(StatusPerkawinan::class)],
            'pekerjaan' => ['required', 'string', 'max:100'],
            'pendidikan' => ['required', 'string', 'max:100'],
            'rt_rw' => ['required', 'string', 'max:7'],
            'desa_id' => ['nullable', 'exists:desa,id'],
            'kecamatan_id' => ['nullable', 'exists:kecamatan,id'],
            'level_desil' => ['required', Rule::enum(LevelDesil::class)],
        ];
    }
}
