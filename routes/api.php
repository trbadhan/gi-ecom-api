<?php

use App\Http\Controllers\api\auth\AdminAuthController;
use App\Http\Controllers\api\auth\UserAuthController;
use App\Http\Controllers\api\category\CategoryController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'v2', 'as' => 'v2.', 'middleware' => ['global.token']], function ($router) {
    Route::prefix('user')->group(function () {
        Route::post('login', [UserAuthController::class, 'login']);
        Route::post('logout', [UserAuthController::class, 'logout'])->middleware('auth:api');
    });

    // ADMIN API
    Route::prefix('admin')->group(function () {
        Route::post('login', [AdminAuthController::class, 'login']);
    });
});


Route::group([
    'prefix' => 'v2',
    'as' => 'v2.',
    'middleware' => ['jwt.verify', 'global.token', 'auth:admin-api']
], function ($router) {
    Route::prefix('admin')->group(function () {
        //Admin user
        Route::post('logout', [AdminAuthController::class, 'logout']);
        Route::post('refresh', [AdminAuthController::class, 'refresh']);
        Route::post('me', [AdminAuthController::class, 'me']);
        Route::post('update-user', [AdminAuthController::class, 'updateUser']);

        //category
        Route::post('categoy-lists', [CategoryController::class, 'index']);
    });
});
