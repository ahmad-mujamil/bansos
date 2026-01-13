<?php

namespace App\Http\Requests;

use App\Enums\RoleUser;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PenggunaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {

        $roles = collect(RoleUser::cases())->map(fn($role) => $role->value);

        return [
            "nama" => ["required", "string", "max:255"],
            "email" => ["required", "email", "unique:users,email," .$this->user?->id??''],
            "username" => ["required", "string", "max:50", "unique:users,username," .$this->user?->id??''],
            "password" => ["required", "string", "min:8"],
            "is_active" => ["required", "boolean"],
            "role" => ["required", "string", Rule::in($roles)],
        ];
    }
}
