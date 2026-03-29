<?php

namespace App\Http\Requests;

use App\Enums\JabatanOrganisasi;
use App\Enums\JenisDokumen;
use App\Enums\JenisOrganisasi;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class KelompokMasyarakatRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    protected function prepareForValidation(): void
    {
        $dokumen = $this->input('dokumen', []);
        if (! is_array($dokumen)) {
            return;
        }
        foreach ($dokumen as $i => $row) {
            if (! is_array($row)) {
                continue;
            }
            if (array_key_exists('id', $row) && $row['id'] === '') {
                $dokumen[$i]['id'] = null;
            }
        }
        $this->merge(['dokumen' => $dokumen]);
    }

    public function rules(): array
    {
        $organisasiId = $this->route('kelompok_masyarakat') ?? null;
        $jenis = $this->input('jenis');

        $base = [
            'nama' => ['required', 'string', 'max:255'],
            'jenis' => ['required', Rule::enum(JenisOrganisasi::class)],
            'nomor' => [
                'required',
                'string',
                'max:100',
                Rule::unique('organisasi', 'nomor')->where('jenis', $jenis)->ignore($organisasiId),
            ],
            'tgl_pembentukan' => ['required', 'date'],
            'kecamatan_id' => ['required', 'exists:kecamatan,id'],
            'desa_id' => ['required', 'exists:desa,id'],
            'is_active' => ['sometimes', 'boolean'],
        ];

        $dokumenIdRules = ['nullable', 'uuid'];
        if ($organisasiId) {
            $dokumenIdRules[] = Rule::exists('organisasi_dokumen', 'id')->where('organisasi_id', $organisasiId);
        }

        return array_merge($base, [
            'anggota' => ['nullable', 'array'],
            'anggota.*.penduduk_id' => ['required', 'exists:penduduk,id', 'distinct'],
            'anggota.*.jabatan' => ['required', Rule::enum(JabatanOrganisasi::class)],
            'dokumen' => ['nullable', 'array'],
            'dokumen.*.id' => $dokumenIdRules,
            'dokumen.*.jenis_dokumen' => ['required', Rule::enum(JenisDokumen::class)],
            'dokumen.*.keterangan' => ['required', 'string', 'max:255'],
            'dokumen.*.file' => ['nullable', 'file', 'mimes:pdf,jpeg,jpg,png,webp', 'max:10240'],
        ]);
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $organisasiId = $this->route('kelompok_masyarakat');
            $dokumen = $this->input('dokumen', []);
            if (! is_array($dokumen)) {
                return;
            }
            foreach ($dokumen as $i => $row) {
                if (! is_array($row)) {
                    continue;
                }
                $hasId = ! empty($row['id'] ?? null);
                $hasFile = $this->hasFile("dokumen.{$i}.file");
                if (! $hasId && ! $hasFile) {
                    $validator->errors()->add(
                        "dokumen.{$i}.file",
                        'Berkas dokumen wajib diunggah untuk entri baru.'
                    );
                }
            }
        });
    }
}
