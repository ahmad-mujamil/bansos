<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePasswordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            "password_old" => ["required", "string", "current_password"],
            "password" => ["required", "string", "min:8", "confirmed", "different:password_old"],
        ];
    }

    public function messages(): array
    {
        return [
            "password_old.required" => "Password lama harus diisi.",
            "password_old.string" => "Password lama harus berupa teks.",
            "password_old.current_password" => "Password lama tidak sesuai.",
            "password.required" => "Password baru harus diisi.",
            "password.string" => "Password baru harus berupa teks.",
            "password.min" => "Password baru minimal 8 karakter.",
            "password.confirmed" => "Konfirmasi password tidak cocok.",
            "password.different" => "Password baru harus berbeda dari password lama.",
        ];
    }
}
