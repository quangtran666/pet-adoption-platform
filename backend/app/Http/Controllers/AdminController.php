<?php

namespace App\Http\Controllers;

use App\Http\Middleware\AdminMiddleware;
use App\Http\Resources\PetResource;
use App\Models\Pet;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
        $this->middleware(AdminMiddleware::class);
    }

    // Get all pending adoption requests
    public function pendingPets(Request $request)
    {
        // Implement pending method
        $pet = Pet::pending()
            ->with(['images', 'user'])
            ->latest()
            ->paginate(10);

        return PetResource::collection($pet);
    }

    public function approvePet(Pet $pet)
    {
        // Only pending pets can be approved
        if ($pet->status !== Pet::STATUS_PENDING) {
            return response()->json(['message' => 'Pet is not pending for approval'], 400);
        }

        $pet->update([
            'status' => Pet::STATUS_ACTIVE
        ]);

        return new PetResource($pet->load(['images', 'user']));
    }

    public function rejectPet(Pet $pet)
    {
        // Only pending pets can be rejected
        if ($pet->status !== Pet::STATUS_PENDING) {
            return response()->json(['message' => 'Pet is not pending for approval'], 400);
        }

        $pet->update([
            'status' => Pet::STATUS_REJECTED
        ]);

        return new PetResource($pet->load(['images', 'user']));
    }
}
