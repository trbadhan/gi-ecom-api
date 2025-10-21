<?php

use App\Http\Controllers\api\auth\AdminAuthController;
use App\Http\Controllers\api\auth\UserAuthController;
use App\Http\Controllers\api\category\CategoryController;
use App\Http\Controllers\api\product\ProductController;
use App\Http\Controllers\api\product\ProductImageController;
use App\Http\Controllers\api\product\ProductPriceController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['global.token']], function ($router) {
    Route::prefix('user')->group(function () {
        Route::post('login', [UserAuthController::class, 'login']);
        Route::post('logout', [UserAuthController::class, 'logout'])->middleware('auth:api');
    });

    // ADMIN API
    Route::prefix('admin')->group(function () {
        Route::post('login', [AdminAuthController::class, 'login']);

        //category
        Route::post('categoy-lists', [CategoryController::class, 'index']);

        //product
        Route::post('product-lists', [ProductController::class, 'index']);
    });
});


Route::group([
    'middleware' => ['jwt.verify', 'global.token', 'auth:admin-api']
], function ($router) {

    //Admin user
    Route::prefix('admin')->group(function () {
        //Auth
        Route::post('logout', [AdminAuthController::class, 'logout']);
        Route::post('refresh', [AdminAuthController::class, 'refresh']);
        Route::post('me', [AdminAuthController::class, 'me']);
        Route::post('update-user', [AdminAuthController::class, 'updateUser']);

        //category
        Route::post('categoy-store', [CategoryController::class, 'store']);
        Route::post('categoy-sort', [CategoryController::class, 'sortData']);
        Route::put('/categoy/{id}/status', [CategoryController::class, 'updateStatus']);
        Route::put('/categoy/{category}', [CategoryController::class, 'update']);
        Route::post('/category/delete', [CategoryController::class, 'destroy']);

        //product
        Route::post('product-store', [ProductController::class, 'store']);
        Route::put('/product/{id}', [ProductController::class, 'update'])->name('products.update');

        //product image
        Route::post('/product-images/store', [ProductImageController::class, 'uploadImages']);
        Route::post('/product-images/update', [ProductImageController::class, 'editImages']);
        Route::delete('product-images/delete/{image}', [ProductImageController::class, 'deleteImage']);

        //product price
        Route::post('/product-price/store', [ProductPriceController::class, 'store']);
        Route::put('product-prices/update/{id}', [ProductPriceController::class, 'update']);
    });
});
