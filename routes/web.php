<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ctrlDatos;


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


require __DIR__.'/auth.php';
