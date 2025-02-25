<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\ForgotPasswordRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Password;

class AuthController extends Controller
{
    public function register(RegisterRequest $request) : JsonResponse
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => \Hash::make($request->password)
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'User registered successfully',
            'user' => new UserResource($user),
            'token' => $token
        ], 201);
    }

    public function login(LoginRequest $request) : JsonResponse {
        $user = User::where('email', $request->email)->first();

        if (!$user || !\Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Invalid credentials'
            ], 401);
        }

        // Revoke previous tokens
        if ($request->boolean('revoke_previous_tokens', false)) {
            $user->tokens()->delete();
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'User logged in successfully',
            'user' => new UserResource($user),
            'token' => $token
        ]);
    }

    public function logout() : JsonResponse {
        auth()->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'User logged out successfully'
        ]);
    }

    public function forgotPassword(ForgotPasswordRequest $request) : JsonResponse
    {
        $status = \Password::sendResetLink($request->only('email'));

        if ($status === Password::RESET_LINK_SENT) {
            return response()->json([
                'message' => __($status),
                'status' => 'email_sent'
            ]);
        }

        return response()-> json([
            'message' => __($status),
            'status' => 'failed'
        ], 400);
    }

    public function resetPassword(ResetPasswordRequest $request) : JsonResponse
    {
        $status = \Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            static function (User $user, string $password) {
                $user->forceFill([
                    'password' => \Hash::make($password)
                ])->setRememberToken(\Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return response()->json(['message' => __($status)]);
        }

        return response()->json(['message' => __($status)], 400);
    }
}
