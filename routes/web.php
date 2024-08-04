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
        Route::get('/produccion/buscar', [ProduccionController::class, 'buscar'])->name('produccion.buscar');
        Route::get('/create', [ProduccionController::class, 'create'])->name('produccion.create');
        Route::post('/create', [ProduccionController::class,'store'])->name('produccion.store');
        Route::get('/{id}/edit', [ProduccionController::class, 'edit'])->name('produccion.edit');
        Route::patch('/{id}/edit', [ProduccionController::class, 'update'])->name('produccion.update');
        Route::delete('/{id}/edit', [ProduccionController::class, 'destroy'])->name('produccion.destroy');
    });
    /*
    //Rutas Notas
    Route::prefix('notas')->group(function(){
        Route::get('/mesactual', [NotasController::class, 'NotaRecepcion'])->name('Notas.mesactual');
        Route::get('/mesanterior/{mesOffset?}', [NotasController::class, 'HistorialNotaRecepcion'])->name('Notas.mesanterior');    
    });

    //Rutas Descarga PDF
    Route::get('/descargarPdf/{rut}/{anio}/{mes}/{filename}', [ControllerDescargaPdf::class, 'descargarPdf'])->name('descargar.pdf');
    */

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
