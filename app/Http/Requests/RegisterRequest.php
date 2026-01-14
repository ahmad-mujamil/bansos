<?php

namespace App\Http\Requests;

use App\Enums\StatusUser;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Allow guest users to register
    }

    public function rules(): array
    {
        $statusValues = [
            StatusUser::PERORANGAN->value,
            StatusUser::ORGANISASI->value
        ];

        return [
            "nama" => ["required", "string", "max:255"],
            "email" => ["required", "email", "max:255", "unique:users,email"],
            "username" => ["required", "string", "max:50", "unique:users,username"],
            "password" => ["required", "string", "min:8", "confirmed"],
            "status" => ["required", "integer", Rule::in($statusValues)],
        ];
    }

    public function messages(): array
    {
        return [
            "nama.required" => "Nama harus diisi.",
            "nama.string" => "Nama harus berupa teks.",
            "nama.max" => "Nama maksimal 255 karakter.",
            "email.required" => "Email harus diisi.",
            "email.email" => "Format email tidak valid.",
            "email.max" => "Email maksimal 255 karakter.",
            "email.unique" => "Email sudah digunakan.",
            "username.required" => "Username harus diisi.",
            "username.string" => "Username harus berupa teks.",
            "username.max" => "Username maksimal 50 karakter.",
            "username.unique" => "Username sudah digunakan.",
            "password.required" => "Password harus diisi.",
            "password.string" => "Password harus berupa teks.",
            "password.min" => "Password minimal 8 karakter.",
            "password.confirmed" => "Konfirmasi password tidak cocok.",
            "status.required" => "Status user harus dipilih.",
            "status.integer" => "Status user harus berupa angka.",
            "status.in" => "Status user tidak valid.",
        ];
    }
}

