<?php

namespace App\Http\Requests;

use App\Enums\BodyPart;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PoseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {

        return [
            "nama" => ["required", "string", "max:255"],
            "deskripsi" => ["required", "string"],
            "gambar" => ["nullable", "image", "mimes:jpeg,jpg,png", "max:2048"],
            "video" => ["required", "string"],
            "body_part" => ["required", Rule::enum(BodyPart::class)],
        ];
    }

}
