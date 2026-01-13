<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ClassroomPasienRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {

        return [
            "pasien_id" => ["required", "string", "exists:pasien,id"]
        ];
    }

}
