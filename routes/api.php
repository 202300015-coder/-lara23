<?php

use App\Http\Controllers\Api\CategoryApiController;
use App\Http\Controllers\Api\ProductApiController;
use Illuminate\Support\Facades\Route;

Route::apiResource('categories', CategoryApiController::class)->names('api.categories');
Route::apiResource('products', ProductApiController::class)->names('api.products');