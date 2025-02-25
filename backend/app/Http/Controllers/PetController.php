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
    public function index()
    {

    }

    public function store(StorePetRequest $request)
    {

    }

    public function show(Pet $pet)
    {

    }

    public function update(UpdatePetRequest $request, Pet $pet)
    {

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pet $pet)
    {

    }
}
