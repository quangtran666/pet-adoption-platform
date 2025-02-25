<?php

namespace App\Http\Controllers;

use App\Http\Requests\Pet\StorePetImageRequest;
use App\Http\Resources\PetImageResource;
use App\Models\Pet;
use App\Models\PetImage;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PetImageController extends Controller
{
    use AuthorizesRequests;

    public function store(StorePetImageRequest $request, Pet $pet)
    {
        $this->authorize('create', [PetImage::class, $pet]);

        $images = [];

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('pets', 'public');

                $images[] = $pet->images()->create([
                    'path' => $path
                ]);
            }
        }

        return PetImageResource::collection($images);
    }

    public function destroy(Pet $pet, PetImage $image)
    {
        $this->authorize('delete', $image);

        if ($image->pet_id !== $pet->id) {
            return response()->json(['message' => 'Image does not belong to this pet'], 403);;
        }

        if (Storage::disk('public')->exists($image->path)) {
            Storage::disk('public')->delete($image->path);
        }

        $image->delete();

        return response()->json(['message' => 'Image deleted successfully'], 204);
    }
}
