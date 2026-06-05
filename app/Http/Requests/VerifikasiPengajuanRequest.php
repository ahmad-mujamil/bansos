<?php

namespace App\Http\Requests;

use App\Enums\RupaBantuan;
use App\Models\PengajuanPemeriksa;
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
            'nilai_rekomendasi' => ['required', 'numeric', 'min:0'],
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

            'pemeriksa' => ['required', 'array', 'min:'.PengajuanPemeriksa::MIN_VERIFIKASI_COUNT, 'max:'.PengajuanPemeriksa::MAX_VERIFIKASI_COUNT],
            'pemeriksa.*.nama' => ['required', 'string', 'max:255'],
            'pemeriksa.*.nip' => ['required', 'string', 'max:18'],
            'pemeriksa.*.jabatan' => ['required', 'string', 'max:255'],
            'lokasi_pengesahan' => ['required', 'string', 'max:255'],
            'disahkan_oleh' => ['required', 'string', 'max:255'],
            'disahkan_nip' => ['required', 'string', 'max:18'],
        ];
    }

    public function messages(): array
    {
        return [
            'rupa_bantuan.in' => 'Rupa bantuan tidak valid.',
            'nilai_rekomendasi.required' => 'Nilai rekomendasi wajib diisi.',
            'nilai_rekomendasi.numeric' => 'Nilai rekomendasi harus berupa angka.',
            'detail.*.penduduk_id.required_with' => 'Nilai harus diisi jika penduduk tersebut dipilih.',
            'detail.*.nilai.required_with' => 'Penduduk harus dipilih jika nilai diisi.',
            'items.*.nama_barang.required_with' => 'Satuan, spesifikasi, harga satuan, dan qty harus diisi jika barang tersebut dipilih.',
            'items.*.satuan.required_with' => 'Nama barang, spesifikasi, harga satuan, dan qty harus diisi jika satuan tersebut dipilih.',
            'items.*.spesifikasi.required_with' => 'Nama barang, satuan, harga satuan, dan qty harus diisi jika spesifikasi tersebut dipilih.',
            'items.*.harga_satuan.required_with' => 'Nama barang, satuan, spesifikasi, dan qty harus diisi jika harga satuan tersebut dipilih.',
            'pemeriksa.min' => 'Minimal '.PengajuanPemeriksa::MIN_VERIFIKASI_COUNT.' data pemeriksa harus diisi.',
            'pemeriksa.max' => 'Pemeriksa paling banyak '.PengajuanPemeriksa::MAX_VERIFIKASI_COUNT.' orang.',
        ];
    }
}
