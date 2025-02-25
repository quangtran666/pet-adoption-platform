<?php

namespace App\Http\Requests\Pet;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\Pet;

class UpdatePetRequest extends FormRequest
{
    public function authorize(): bool
    {
        $pet = Pet::findOrFail($this->route('pet'));
        return $pet->user_id === Auth::id();
    }

    public function rules(): array
    {
        return [
            'name' => 'sometimes|string|max:255',
            'age' => 'sometimes|string|max:255',
            'type' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'location' => 'sometimes|string|max:255',
        ];
    }
}
