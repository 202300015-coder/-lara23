<?php

use App\Http\Controllers\Api\CategoryApiController;
use App\Http\Controllers\Api\ProductApiController;
use Illuminate\Support\Facades\Route;

Route::prefix('categories')->group(function () {
    Route::get('/', [CategoryApiController::class, 'index']);
    Route::post('/', [CategoryApiController::class, 'store']);
    Route::get('/{category}', [CategoryApiController::class, 'show']);
    Route::put('/{category}', [CategoryApiController::class, 'update']);
    Route::delete('/{category}', [CategoryApiController::class, 'destroy']);
});

Route::prefix('products')->group(function () {
    Route::get('/', [ProductApiController::class, 'index']);
    Route::post('/', [ProductApiController::class, 'store']);
    Route::get('/{product}', [ProductApiController::class, 'show']);
    Route::put('/{product}', [ProductApiController::class, 'update']);
    Route::delete('/{product}', [ProductApiController::class, 'destroy']);
});