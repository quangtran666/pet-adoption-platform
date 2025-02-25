<?php

namespace App\Http\Requests\Pet;

use App\Models\Pet;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StorePetImageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            "images" => "required|array|min:1",
            "images.*" => "required|image|mimes:jpeg,png,jpg,gif,svg|max:2048"
        ];
    }
}
