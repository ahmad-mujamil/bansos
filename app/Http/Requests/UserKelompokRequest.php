<?php

namespace App\Http\Requests;

use App\Enums\RoleUser;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Enums\JenisUser;

class UserKelompokRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {

        $roles = collect(RoleUser::cases())->map(fn($role) => $role->value);
        $userId = $this->route('user-kelompok')?->id ?? null;

        return [
            "nama" => ["required", "string", "max:255"],
            "email" => ["required", "email", Rule::unique('users', 'email')->ignore($userId)],
            "username" => ["required", "string", "max:50", Rule::unique('users', 'username')->ignore($userId)],
            "password" => [$userId ? "nullable" : "required", "string", "min:8"],
            "is_active" => ["required", "boolean"],
            "role" => ["required", "string", Rule::in($roles)],
            "opd_id" => ["nullable", "uuid", "exists:opd,id"],
        

            'alamat' => ["required", "string", "max:255"],
            "phone" => ["required", "string", "max:15"],
            "nik" => ["required", "string", "max:20"],
            "desa_id" => ["nullable", "uuid", "exists:desa,id"],
            "kecamatan_id" => ["nullable", "uuid", "exists:kecamatan,id"],
            "organisasi_id" => ["nullable", "uuid", "exists:organisasi,id"],
            "verification_status" => ["required", "string"],
            "verified_at" => ["required", "date"],
            "verified_by" => ["required", "exists:users,id"],
            'verification_note' => ["nullable", "string", "max:255"],
            'type' => ["required", "string"],

        ];
    }

    protected function prepareForValidation()
    {
        return $this->merge([
            'role' =>  'user',
            'opd_id' =>  auth()->user()->opd_id ?? null,
            'verified_at' => now(),
            'verified_by' => auth()->id(),
            'verification_status' => 'approved',
            'verification_note' => 'Diinput oleh opd',
            'type' => 'KLP'
        ]);
    }
}
