<?php

namespace App\Http\Requests;

use App\Enums\JenisPengajuan;
use App\Models\Pengajuan;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BastRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('nilai_rekomendasi')) {
            $clean = preg_replace('/[^\d]/', '', (string) $this->input('nilai_rekomendasi'));
            $this->merge(['nilai_rekomendasi' => $clean === '' ? null : $clean]);
        }
    }

    public function rules(): array
    {
        $bastId = $this->route('bast')?->id ?? null;
        $isUpdate = $bastId !== null;

        // Untuk pengajuan subsidi bunga, nilai rekomendasi kredit diisi saat BAST.
        $isSubsidiBunga = $this->input('pengajuan_id')
            && Pengajuan::query()
                ->whereKey($this->input('pengajuan_id'))
                ->where('kategori_pengajuan', JenisPengajuan::SUBSIDI_BUNGA->value)
                ->exists();

        return [
            'pengajuan_id' => ['required', 'uuid', 'exists:pengajuan,id'],
            'nomor'        => ['required', 'string', 'max:255', Rule::unique('bast', 'nomor')->ignore($bastId)],
            'tanggal'      => ['required', 'date_format:d-m-Y'],
            'penerima'     => ['required', 'string', 'max:255'],
            'nilai_rekomendasi' => [$isSubsidiBunga && ! $isUpdate ? 'required' : 'nullable', 'numeric', 'min:0'],
            'dokumen'      => [$isUpdate ? 'nullable' : 'required', 'file', 'mimes:pdf', 'max:5120'],
            'foto'         => [$isUpdate ? 'nullable' : 'required', 'array', 'min:1'],
            'foto.*'       => ['image', 'mimes:jpeg,jpg,png,gif,webp', 'max:2048'],
        ];
    }
}