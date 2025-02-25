<?php

namespace App\Policies;

use App\Models\Pet;
use App\Models\PetImage;
use App\Models\User;

class PetImagePolicy
{
    /**
     * Determine whether the user can create models.
     */
    public function create(User $user, Pet $pet): bool
    {
        return $user->id === $pet->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, PetImage $petImage): bool
    {
        return $user->id === $petImage->pet()->user_id;
    }
}
