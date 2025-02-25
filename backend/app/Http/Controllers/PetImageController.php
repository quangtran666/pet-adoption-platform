<?php

namespace App\Http\Controllers;

use App\Http\Requests\Pet\StorePetImageRequest;
use App\Models\Pet;
use App\Models\PetImage;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PetImageController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePetImageRequest $request, Pet $pet)
    {

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pet $pet, PetImage $image)
    {

    }
}
