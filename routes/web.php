<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProduccionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RegistroController;
use App\Http\Controllers\PasswordChangeController;
use App\Http\Controllers\UsuariosController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;


// Redirecciona root hacia dashboard
Route::get('/', [DashboardController::class, 'redirectToDashboard'])
    ->middleware(['auth', 'verified']);

// Dashboard
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');


//Autentificación usuario Routes
Route::middleware('auth')->group(function () {

    //Rutas Perfiles
    Route::prefix('profile')->group(function () {
        Route::get('/', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });

    Route::prefix('produccion')->group(function () {
        Route::get('/', [ProduccionController::class, 'index'])->name('produccion.index');
        Route::get('/buscar', [ProduccionController::class, 'buscar'])->name('produccion.buscar');
        Route::get('/create', [ProduccionController::class, 'create'])->name('produccion.create');
        Route::post('/create', [ProduccionController::class,'store'])->name('produccion.store');
        Route::get('/{id}/edit', [ProduccionController::class, 'edit'])->name('produccion.edit');
        Route::patch('/{id}/edit', [ProduccionController::class, 'update'])->name('produccion.update');
        Route::delete('/{id}/delete', [ProduccionController::class, 'destroy'])->name('produccion.destroy');
        Route::get('/{id}/printer', [ProduccionController::class,'printer'])->name('produccion.printer');
    });

    //Cambio de Contraseña
    Route::get('password/change', [PasswordChangeController::class, 'showChangeForm'])->name('password.change');
    Route::post('password/change', [PasswordChangeController::class, 'change']);
});

    //Rutas Administrador
    Route::middleware(['auth','admin'])->prefix('admin')->group(function(){
        Route::get('/settings', [RegistroController::class, 'index'])->name('register');
        Route::post('/settings', [RegistroController::class, 'store'])->name('register.store');


        Route::get('/users', [UsuariosController::class,'index'])->name('usuarios');
        Route::get('/users/edit/{id}', [UsuariosController::class, 'edit'])->name('usuarios.edit');
        Route::put('/users/update/{id}', [UsuariosController::class, 'update'])->name('usuarios.update');
        Route::delete('/users/{id}', [UsuariosController::class, 'destroy'])->name('usuarios.destroy');

    });


require __DIR__.'/auth.php';
