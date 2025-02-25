<?php

namespace App\Policies;

use App\Models\AdoptionRequest;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class AdoptionRequestPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, AdoptionRequest $adoptionRequest): bool
    {
        // User can view adoption request if they're  either:
        // 1. The user who made the request
        // 2. The user who owns the pet
        return $user->id === $adoptionRequest->user_id || $user->id === $adoptionRequest->pet->user_id;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, AdoptionRequest $adoptionRequest): bool
    {
        // Only the owner of the pet can update the adoption request
        return $user->id === $adoptionRequest->pet->user_id;
    }

    public function delete(User $user, AdoptionRequest $adoptionRequest): bool
    {
        // User can delete adoption request if they're the user who made the request and the request is still pending
        return $user->id === $adoptionRequest->user_id && $adoptionRequest->status === AdoptionRequest::STATUS_PENDING;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, AdoptionRequest $adoptionRequest): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, AdoptionRequest $adoptionRequest): bool
    {
        return false;
    }
}
