<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdoptionRequestController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PetController;
use App\Http\Controllers\PetImageController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\AdminMiddleware;
use Illuminate\Support\Facades\Route;


// Auth
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

// Users
Route::prefix('user')->middleware('auth:sanctum')->group(function () {
    Route::get('/profile', [UserController::class, 'profile'])->name('user.profile');
    Route::put('/profile', [UserController::class, 'updateProfile'])->name('user.update-profile');
    Route::put('/password', [UserController::class, 'changePassword'])->name('user.change-password');
    Route::delete('/account', [UserController::class, 'deleteAccount'])->name('user.delete-account');
});

// Admins
Route::prefix('admin')->middleware(['auth:sanctum', AdminMiddleware::class])->group(function () {
    Route::get('/pet/pending', [AdminController::class, 'pendingPets'])->name('admin.pet.pending');
    Route::put('/pet/{pet}/approve', [AdminController::class, 'approvePet'])->name('admin.pet.approve');
    Route::put('/pet/{pet}/reject', [AdminController::class, 'rejectPet'])->name('admin.pet.reject');
});

// Pets
Route::apiResource('pets', PetController::class)->only(['index', 'show']);

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('pets', PetController::class)->only(['store', 'update', 'destroy']);
    Route::apiResource('pets.images', PetImageController::class)->only(['store', 'destroy']);
    Route::post("/pets/{pet}/adoption-requests", [AdoptionRequestController::class, 'store'])->name('pets.store.adoption-requests');
});

// Adoption Request
Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('adoption-requests', AdoptionRequestController::class)->except(['store']);
});

// Notifications
Route::middleware('auth:sanctum')->group(function () {
    Route::get("/notifications", [NotificationController::class, "index"])->name('notifications.index');
    Route::put("/notifications/{notification}", [NotificationController::class, "markAsRead"])
        ->where('notification', '[0-9]+')
        ->name('notifications.mark-as-read');
    Route::put("/notifications/read-all", [NotificationController::class, "markAllAsRead"])->name('notifications.mark-all-as-read');
});
