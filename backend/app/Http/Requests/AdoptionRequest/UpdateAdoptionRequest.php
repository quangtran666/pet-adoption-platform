<?php

namespace App\Http\Requests\AdoptionRequest;

use App\Models\AdoptionRequest;
use Illuminate\Foundation\Http\FormRequest;

class UpdateAdoptionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'status' => 'required|string|in:' . AdoptionRequest::STATUS_ACCEPTED . ',' . AdoptionRequest::STATUS_REJECTED,
            'message' => 'required|string',
        ];
    }
}
