<?php

namespace App\Http\Controllers;

use App\Http\Requests\AdoptionRequest\StoreAdoptionRequest;
use App\Http\Requests\AdoptionRequest\UpdateAdoptionRequest;
use App\Http\Resources\AdoptionRequestResource;
use App\Models\AdoptionRequest;
use App\Models\Pet;
use App\Notifications\AdoptionRequestReceived;
use App\Notifications\AdoptionRequestStatusChanged;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class AdoptionRequestController extends Controller
{
    use AuthorizesRequests;

    public function __construct()
    {
        $this->authorizeResource(AdoptionRequest::class, 'adoption_request');
    }

    // Get All the sent request or received request
    public function index(Request $request)
    {
        $user = \Auth::user();
        $type = $request->get('type', 'received');

        if ($type === 'sent') {
            $adoptionRequest = AdoptionRequest::where('user_id', $user->id)
                ->with(['pet', 'user', 'pet.images'])
                ->latest()
                ->paginate(10);
        } else {
            $adoptionRequest = AdoptionRequest::whereHas('pet', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
                ->with(['pet', 'pet.images'])
                ->latest()
                ->paginate(10);
        }

        return AdoptionRequestResource::collection($adoptionRequest);
    }

    // Create a new adoption request
    public function store(StoreAdoptionRequest $request, Pet $pet)
    {
        // Check if the pet is available for adoption
        if ($pet->status !== Pet::STATUS_ACTIVE) {
            return response()->json(['message' => 'Pet is not available for adoption'], 400);
        }

        // Check if the user has already sent a request for this pet
        $existingRequest = AdoptionRequest::where('pet_id', $pet->id)
            ->where('user_id', \Auth::id())
            ->first();

        if ($existingRequest) {
            return response()->json(['message' => 'You have already sent a request for this pet'], 400);
        }

        // Check if the user is the owner of the pet
        if ($pet->user_id === \Auth::id()) {
            return response()->json(['message' => 'You cannot send a request for your own pet'], 400);
        }

        $adoptionRequest = AdoptionRequest::create([
            'user_id' => \Auth::id(),
            'pet_id' => $pet->id,
            'message' => $request->message,
            'status' => AdoptionRequest::STATUS_PENDING
        ]);

        $petOwner = $pet->user;
        $petOwner->notify(new AdoptionRequestReceived($adoptionRequest));

        return new AdoptionRequestResource($adoptionRequest->load(['pet', 'pet.images', 'user']));
    }

    public function show(AdoptionRequest $request)
    {
        return new AdoptionRequestResource($request->load(['pet', 'pet.images', 'user']));
    }

    // Accept or Reject the adoption request
    public function update(UpdateAdoptionRequest $request, AdoptionRequest $adoptionRequest)
    {
        // Update the status and message of the adoption request
        $adoptionRequest->update([
            'status' => $request->status,
            'message' => $request->message
        ]);

        // If the request is accepted then update the pet status to adopted
        if ($request->status === AdoptionRequest::STATUS_ACCEPTED) {
            // Update pet status to adopted
            $adoptionRequest->pet->update([
                'status' => Pet::STATUS_ADOPTED
            ]);

            // Reject all other requests for the same pet
            AdoptionRequest::where('pet_id', $adoptionRequest->pet_id)
                ->where('id', '!=', $adoptionRequest->id)
                ->update([
                    'status' => AdoptionRequest::STATUS_REJECTED
                ]);
        }

        $adoptionRequest->user->notify(new AdoptionRequestStatusChanged($adoptionRequest));

        return new AdoptionRequestResource($adoptionRequest->load(['pet', 'pet.images', 'user']));
    }


    // Delete the adoption request
    public function destroy(AdoptionRequest $adoptionRequest)
    {
        if ($adoptionRequest->status !== AdoptionRequest::STATUS_PENDING) {
            return response()->json(['message' => 'You cannot delete a request that is not pending'], 400);
        }

        $adoptionRequest->delete();

        return response()->json(['message' => 'Request deleted successfully'], 204);
    }
}
