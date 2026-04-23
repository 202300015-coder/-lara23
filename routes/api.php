<?php

use App\Http\Controllers\Api\CategoryApiController;
use App\Http\Controllers\Api\ProductApiController;
use Illuminate\Support\Facades\Route;

Route::apiResource('categories', CategoryApiController::class);
Route::apiResource('products', ProductApiController::class);