<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PetController;
use App\Http\Controllers\PetImageController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;


Route::prefix('auth')->group(function () {
    Route::post("/register", [AuthController::class, 'register'])->name('auth.register');
    Route::post("/login", [AuthController::class, 'login'])->name('auth.login');
    Route::post("/forgot-password", [AuthController::class, 'forgotPassword'])->name('auth.forgot-password');
    Route::post("/reset-password", [AuthController::class, 'resetPassword'])->name('auth.reset-password');

    // Temp: This is a dummy route to satisfy Lavarel route generation
    // The actual reset logic will happen on client side
    Route::get("/reset-password/{token}", static function () {
        return response()->json([
            'message' => 'Password reset link is valid'
        ]);
    })->name('password.reset');

    Route::middleware('auth:sanctum')->group(function () {
        Route::post("/logout", [AuthController::class, 'logout'])->name('auth.logout');
    });
});

Route::prefix('user')->middleware('auth:sanctum')->group(function () {
    Route::get('/profile', [UserController::class, 'profile'])->name('user.profile');
    Route::put('/profile', [UserController::class, 'updateProfile'])->name('user.update-profile');
    Route::put('/password', [UserController::class, 'changePassword'])->name('user.change-password');
    Route::delete('/account', [UserController::class, 'deleteAccount'])->name('user.delete-account');
});

Route::apiResource('pets', PetController::class)->only(['index', 'show']);

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('pets', PetController::class)->only(['store', 'update', 'destroy']);
    Route::apiResource('pets.images', PetImageController::class)->only(['store', 'destroy']);
});
