<?php

use App\Http\Controllers\ControllerDescargaPdf;
use App\Http\Controllers\NotasController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RegistroController;
use App\Http\Controllers\PasswordChangeController;
use Illuminate\Support\Facades\Route;


//Redirecciona root hacia dashboard
Route::get('/', function () {
    return redirect()->route('dashboard');
})->middleware(['auth', 'verified']);


//Dashboard
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


//Autentificación usuario Routes
Route::middleware('auth')->group(function () {  

    //Rutas Perfiles
    Route::prefix('profile')->group(function () {
        Route::get('/', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });

    //Rutas Notas
    Route::prefix('notas')->group(function(){
        Route::get('/mesactual', [NotasController::class, 'NotaRecepcion'])->name('Notas.mesactual');
        Route::get('/mesanterior/{mesOffset?}', [NotasController::class, 'HistorialNotaRecepcion'])->name('Notas.mesanterior');    
    });

    //Rutas Descarga PDF
    Route::get('/descargarPdf/{rut}/{anio}/{mes}/{filename}', [ControllerDescargaPdf::class, 'descargarPdf'])->name('descargar.pdf');

    //Cambio de Contraseña
    Route::get('password/change', [PasswordChangeController::class, 'showChangeForm'])->name('password.change');
    Route::post('password/change', [PasswordChangeController::class, 'change']);
});

    //Rutas Administrador
    Route::middleware(['auth','admin'])->prefix('admin')->group(function(){
        Route::get('/settings', [RegistroController::class, 'index'])->name('register');
        Route::post('/settings', [RegistroController::class, 'store'])->name('register.store');
    });


require __DIR__.'/auth.php';
