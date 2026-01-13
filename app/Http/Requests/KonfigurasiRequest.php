<?php

namespace App\Http\Requests;

use App\Enums\RoleUser;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class KonfigurasiRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {

        return [
            "konfigurasi_id" => "nullable",
            "hari_wd" => ["required", "array"],
            "persen_transfer_bonus" => ["required", "numeric"],
            "batas_hari_vakum" => ["required", "numeric"],
            "kurs" => ["required", "numeric"],
        ];
    }

    protected function prepareForValidation()
    {

        return $this->merge([
            "hari_wd" => array_map('intval', $this->hari_wd) ,
            "kurs" => str_replace(",", "", $this->kurs),
        ]);
    }
}
