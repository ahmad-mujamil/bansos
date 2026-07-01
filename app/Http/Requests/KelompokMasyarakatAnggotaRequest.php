<?php

namespace App\Http\Requests;

use App\Enums\JabatanOrganisasi;
use App\Models\OrganisasiDetail;
use App\Models\Scopes\TahunAnggaranScope;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class KelompokMasyarakatAnggotaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        $organisasiId = $this->route('kelompok_masyarakat');
        $anggotaId = $this->route('anggota');

        // Saat edit, cek keunikan pada TAHUN milik baris yang diedit (bukan tahun
        // terpilih di UI), supaya mengubah jabatan saja tidak salah dianggap duplikat.
        $tahun = tahun_aktif();
        if ($anggotaId) {
            $anggota = OrganisasiDetail::query()
                ->withoutGlobalScope(TahunAnggaranScope::class)
                ->find($anggotaId);
            if ($anggota) {
                $tahun = (int) $anggota->tahun_anggaran;
            }
        }

        return [
            'penduduk_id' => [
                'required',
                'exists:penduduk,id',
                Rule::unique('organisasi_detail', 'penduduk_id')
                    ->where('organisasi_id', $organisasiId)
                    ->where('tahun_anggaran', $tahun)
                    ->ignore($anggotaId),
            ],
            'jabatan' => ['required', Rule::enum(JabatanOrganisasi::class)],
        ];
    }

    public function messages(): array
    {
        return [
            'penduduk_id.unique' => 'Penduduk tersebut sudah tercatat sebagai anggota organisasi ini.',
        ];
    }
}
