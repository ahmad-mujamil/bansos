<?php

namespace App\Http\Requests;

use App\Enums\PengajuanStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class VerifikasiPengajuanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }

    public function rules(): array
    {
        $ditolak = PengajuanStatus::DITOLAK->value;

        return [
            'status' => [
                'required',
                Rule::in([
                    PengajuanStatus::DISETUJUI->value,
                    $ditolak,
                ]),
            ],
            'catatan' => [
                'nullable',
                'string',
                'max:1000',
                Rule::requiredIf(in_array($this->input('keputusan'), [$ditolak], true)),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'status.in' => 'Keputusan verifikasi tidak valid.',
        ];
    }
}
