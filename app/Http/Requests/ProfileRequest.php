<?php

namespace App\Http\Requests;

use App\Enums\RoleUser;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {

        return [
            "nama" => ["required", "string", "max:255"],
            "email" => ["required", "email", "unique:users,email," .auth()->id()??''],
            "no_hp" => ["required", "string", "max:15"],
            "alamat" => ["required", "string"],
            "foto" => ["nullable", "image", "mimes:jpg,jpeg,png", "max:2048"],
        ];
    }
}
