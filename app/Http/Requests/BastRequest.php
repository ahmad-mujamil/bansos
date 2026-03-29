<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BastRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        $bastId = $this->route('bast')?->id ?? null;
        $isUpdate = $bastId !== null;

        return [
            'pengajuan_id' => ['required', 'uuid', 'exists:pengajuan,id'],
            'nomor'        => ['required', 'string', 'max:255', Rule::unique('bast', 'nomor')->ignore($bastId)],
            'tanggal'      => ['required', 'date_format:d-m-Y'],
            'penerima'     => ['required', 'string', 'max:255'],
            'dokumen'      => [$isUpdate ? 'nullable' : 'required', 'file', 'mimes:pdf', 'max:5120'],
            'foto'         => [$isUpdate ? 'nullable' : 'required', 'array', 'min:1'],
            'foto.*'       => ['image', 'mimes:jpeg,jpg,png,gif,webp', 'max:2048'],
        ];
    }
}