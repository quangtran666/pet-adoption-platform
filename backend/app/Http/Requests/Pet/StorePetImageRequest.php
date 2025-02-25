<?php

namespace App\Http\Requests\Pet;

use App\Models\Pet;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StorePetImageRequest extends FormRequest
{
    public function authorize(): bool
    {
        $pet = Pet::findOrFail($this->route("pet"));
        return $pet->user_id === Auth::id();
    }

    public function rules(): array
    {
        return [
            "images" => "required|array|min:1",
            "images.*" => "required|image|mimes:jpeg,png,jpg,gif,svg|max:2048"
        ];
    }
}
