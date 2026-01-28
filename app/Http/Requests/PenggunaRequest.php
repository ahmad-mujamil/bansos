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
        $userId = $this->route('pengguna')?->id ?? null;

        return [
            "nama" => ["required", "string", "max:255"],
            "email" => ["required", "email", Rule::unique('users', 'email')->ignore($userId)],
            "username" => ["required", "string", "max:50", Rule::unique('users', 'username')->ignore($userId)],
            "password" => [$userId ? "nullable" : "required", "string", "min:8"],
            "is_active" => ["required", "boolean"],
            "role" => ["required", "string", Rule::in($roles)],
            "opd_id" => ["nullable", "uuid", "exists:opd,id"],
        ];
    }
}
