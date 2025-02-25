<?php

namespace App\Http\Controllers;

use App\Http\Requests\Pet\StorePetRequest;
use App\Http\Requests\Pet\UpdatePetRequest;
use App\Http\Resources\PetResource;
use App\Models\Pet;
use App\Models\PetImage;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PetController extends Controller
{
    use AuthorizesRequests;

    public function __construct()
    {
        $this->authorizeResource(Pet::class, 'pet', [
            'except' => ['index', 'show']
        ]);
    }

    public function index(Request $request)
    {
        $query = Pet::query()->with(['images', 'user']);

        if ($request->has('type')) {
            $query->ofType($request->type);
        }

        if ($request->has('location')) {
            $query->inLocation($request->location);
        }

        if ($request->has('status')) {
            $status = $request->status;
            if ($status === 'active') {
                $query->active();
            } else if ($status === 'pending') {
                $query->pending();
            } else if ($status === 'adopted') {
                $query->adopted();
            }
        } else {
            $query->active();
        }

        if ($request->has('search')) {
            $query->search($request->search);
        }

        $query->latest();

        $pet = $query->paginate(10);

        return PetResource::collection($pet);
    }

    public function store(StorePetRequest $request)
    {
        $validated = $request->validated();

        return DB::transaction(function () use ($validated, $request) {
            $pet = Pet::create([
                'user_id' => Auth::id(),
                'status' => Pet::STATUS_PENDING,
                'name' => $validated['name'],
                'age' => $validated['age'],
                'type' => $validated['type'],
                'description' => $validated['description'],
                'location' => $validated['location']
            ]);

            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $path = $image->store('pets', 'public');

                    $pet->images()->create([
                       'path' => $path
                    ]);
                }
            }

            $pet->load('images');

            return new PetResource($pet);
        });
    }

    public function show(Pet $pet)
    {
        $pet->load(['images', 'user']);

        return new PetResource($pet);
    }

    public function update(UpdatePetRequest $request, Pet $pet)
    {
        $pet->update($request->validated());

        $pet->load(['images', 'user']);

        return new PetResource($pet);
    }

    public function destroy(Pet $pet)
    {
        return DB::transaction(function () use ($pet) {
            foreach ($pet->images as $image) {
                if (Storage::disk('public')->exists($image->path)) {
                    Storage::disk('public')->delete($image->path);
                }
            }

            $pet->delete();

            return response()->json(['message' => 'Pet deleted successfully'], 204);
        });
    }
}
