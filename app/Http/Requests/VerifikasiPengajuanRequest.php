<?php

namespace App\Http\Requests;

use App\Enums\RupaBantuan;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class VerifikasiPengajuanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }

    public function rules(): array
    {
        return [
            'lulus_kriteria' => ['required', 'boolean'],
            'lulus_administrasi' => ['required', 'boolean'],
            'lulus_kesesuaian' => ['required', 'boolean'],
            'sesuai_program_pemda' => ['required', 'boolean'],
            'nilai_rekomendasi' => ['nullable', 'numeric', 'min:0'],
            'rupa_bantuan' => ['nullable', Rule::in(array_map(fn (RupaBantuan $e) => $e->value, RupaBantuan::cases()))],

            'detail' => ['nullable', 'array'],
            'detail.*.penduduk_id' => ['required_with:detail.*.nilai', 'uuid'],
            'detail.*.nilai' => ['required_with:detail.*.penduduk_id', 'numeric', 'min:0'],

            'items' => ['nullable', 'array'],
            'items.*.nama_barang' => ['required_with:items.*.satuan,items.*.spesifikasi,items.*.harga_satuan,items.*.qty', 'string', 'max:255'],
            'items.*.satuan' => ['required_with:items.*.nama_barang,items.*.spesifikasi,items.*.harga_satuan,items.*.qty', 'string', 'max:50'],
            'items.*.spesifikasi' => ['required_with:items.*.nama_barang,items.*.satuan,items.*.harga_satuan,items.*.qty', 'string', 'max:2000'],
            'items.*.harga_satuan' => ['required_with:items.*.nama_barang,items.*.satuan,items.*.spesifikasi,items.*.qty', 'numeric', 'min:0'],
            'items.*.qty' => ['required_with:items.*.nama_barang,items.*.satuan,items.*.spesifikasi,items.*.harga_satuan', 'integer', 'min:1'],

            'nama_barang' => ['nullable', 'string', 'max:255'],
            'satuan' => ['nullable', 'string', 'max:50'],
            'spesifikasi' => ['nullable', 'string', 'max:2000'],
            'harga_satuan' => ['nullable', 'numeric', 'min:0'],
            'qty' => ['nullable', 'integer', 'min:1'],

            'catatan' => [
                'nullable',
                'string',
                'max:1000',
            ],

            'pemeriksa' => ['required', 'array', 'size:3'],
            'pemeriksa.*.nama' => ['required', 'string', 'max:255'],
            'pemeriksa.*.nip' => ['required', 'string', 'max:50'],
            'pemeriksa.*.jabatan' => ['required', 'string', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'rupa_bantuan.in' => 'Rupa bantuan tidak valid.',
        ];
    }
}
