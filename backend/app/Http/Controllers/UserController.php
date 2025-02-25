<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\ChangePasswordRequest;
use App\Http\Requests\User\UpdateProfileRequest;
use App\Http\Resources\UserResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class UserController extends Controller
{
    public function profile() : JsonResponse
    {
        return response()->json([
            'user' => new UserResource(auth()->user())
        ]);
    }

    public function updateProfile(UpdateProfileRequest $request) : JsonResponse
    {
        $user = auth()->user();

        $user->update($request->validated());

        return response()->json([
            'message' => 'Profile updated successfully',
            'user' => new UserResource($user)
        ]);
    }

    public function changePassword(ChangePasswordRequest $request) : JsonResponse
    {
        $user = auth()->user();

        $user->update([
            'password' => \Hash::make($request->password)
        ]);

        return response()->json([
            'message' => 'Password changed successfully'
        ]);
    }

    public function deleteAccount() : JsonResponse
    {
        $user = auth()->user();

        $user->tokens()->delete();

        $user->delete();

        return response()->json([
            'message' => 'Account deleted successfully'
        ]);
    }
}
