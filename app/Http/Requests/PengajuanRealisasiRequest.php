<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PengajuanRealisasiRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    protected function prepareForValidation(): void
    {
        $tgl = $this->input('tanggal_laporan');
        if ($tgl === '' || $tgl === null) {
            $this->merge(['tanggal_laporan' => null]);
        }
    }

    public function rules(): array
    {
        $this->route('pengajuan')->loadMissing('realisasi');

        $isUpdate = $this->route('pengajuan')->realisasi !== null;

        return [
            'tanggal_laporan' => ['nullable', 'date_format:d-m-Y'],
            'keterangan' => ['required', 'string', 'max:10000'],
            'dokumen' => $isUpdate
                ? ['nullable', 'file', 'mimes:pdf', 'max:5120']
                : ['required', 'file', 'mimes:pdf', 'max:5120'],
        ];
    }
}
