<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdoptionRequestResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'status' => $this->status,
            'message' => $this->message,
            'pet' => $this->whenLoaded('pet', function () {
                return [
                    'name' => $this->pet->name,
                    'age' => $this->pet->age,
                    'type' => $this->pet->type,
                    'description' => $this->pet->description,
                    'location' => $this->pet->location,
                    'status' => $this->pet->status,
                    'owner' => $this->whenLoaded('user', function () {
                        return [
                            'name' => $this->pet->user->name,
                            'avatar' => $this->pet->user->avatar,
                        ];
                    }),
                    'images' => PetImageResource::collection($this->pet->images)
                ];
            })
        ];
    }
}
