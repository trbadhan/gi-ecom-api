<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AdminAuthController;
use App\Http\Controllers\Api\CategoryController;

Route::prefix('admin')->group(function () {
    Route::post('/login', [AdminAuthController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AdminAuthController::class, 'logout']);
        Route::get('/me', [AdminAuthController::class, 'me']);

        // example protected route
        Route::get('/dashboard', function () {
            return response()->json(['message' => 'Welcome to Admin Dashboard']);
        });

        Route::apiResource('categories', CategoryController::class);
        Route::post('/categories/reorder', [CategoryController::class, 'reorder']);
    });
});
