<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'avatar' => 'sometimes|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'description' => 'sometimes|string|max:255',
        ];
    }
}
