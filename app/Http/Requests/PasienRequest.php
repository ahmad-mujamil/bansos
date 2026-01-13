<?php

namespace App\Http\Requests;

use App\Enums\RoleUser;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PasienRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {

        return [
            "nomor_rm" => ["required", "string", "max:50", Rule::unique('pasien','nomor_rm')->ignore($this->pasien)],
            "nama" => ["required", "string", "max:255"],
            "alamat" => ["required", "string"],
            "jenis_kelamin" => ["required", "string", "in:L,P"],
            "tgl_lahir" => ["required", "date:Y-m-d"],
        ];
    }
}
