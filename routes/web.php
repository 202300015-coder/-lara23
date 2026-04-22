<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ctrlDatos;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;


Route::get('/', [ctrlDatos::class, 'ApiComedyHosted']);

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
});
Route::get('/datos', [ctrlDatos::class, 'AccesoDatosVista']);
Route::get('/datos-link', [ctrlDatos::class, 'AccesoDatosVistaLink']);
Route::get('/api-mia', [ctrlDatos::class, 'AccesoDatosApiMia']);
Route::get('/api/comedy-hosted', [ctrlDatos::class, 'ApiComedyHosted']);
Route::get('/view-mio', [ctrlDatos::class, 'AccesoDatosViewMio']);
Route::get('/viewmio', [ctrlDatos::class, 'AccesoDatosViewMio']);
//modificar esto
Route::get('/detalle-api/{id}', [ctrlDatos::class, 'detalle'])->name('tj.detalle');

Route::get('/categorias', [CategoryController::class, 'index'])->name('categories.index');
Route::post('/categorias', [CategoryController::class, 'store'])->name('categories.store');
Route::get('/categorias/{category}/edit', [CategoryController::class, 'edit'])->name('categories.edit');
Route::put('/categorias/{category}', [CategoryController::class, 'update'])->name('categories.update');
Route::delete('/categorias/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');

Route::get('/productos', [ProductController::class, 'index'])->name('products.index');
Route::post('/productos', [ProductController::class, 'store'])->name('products.store');
Route::get('/productos/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
Route::put('/productos/{product}', [ProductController::class, 'update'])->name('products.update');
Route::delete('/productos/{product}', [ProductController::class, 'destroy'])->name('products.destroy');

require __DIR__.'/auth.php';
