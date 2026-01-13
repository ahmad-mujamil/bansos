<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GradeNilaiRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {

        return [
            "nama" => ["required", "string", "max:255"],
            "min" => ["required", "numeric", "min:0", "max:10","lte:max"],
            "max" => ["required", "numeric:", "gte:min"],
            "jenis_penilaian" => ["required", "string"],
        ];
    }

}
