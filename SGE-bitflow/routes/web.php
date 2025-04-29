<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Cliente\ClienteController;
use App\Http\Controllers\Cliente\ContactoClienteController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/




Route::get('/clientes/{cliente}/contactos/create', [ContactoClienteController::class, 'create'])
    ->name('clientes.contactos.create');

Route::middleware('auth')->group(function () {

    // Rutas de Clientes
    Route::resource('clientes', ClienteController::class);

    // Rutas de Contactos individuales
    Route::resource('contactos', ContactoController::class)->except(['create', 'show']); 

    // Rutas de contactos que pertenecen a un cliente
    Route::prefix('clientes/{cliente}')->name('clientes.')->group(function () {
        Route::resource('contactos', ContactoClienteController::class)->except(['create', 'show']);
    });

    // Rutas del perfil
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'edit'])->name('edit');
        Route::patch('/', [ProfileController::class, 'update'])->name('update');
        Route::delete('/', [ProfileController::class, 'destroy'])->name('destroy');
    });

    // Dashboard
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->middleware('verified')->name('dashboard');

    // Roles
    Route::get('/roles', function () {
        return view('roles');
    })->middleware('verified')->name('roles');

});

// Página de bienvenida
Route::get('/', function () {
    return view('welcome');
});

require __DIR__.'/auth.php';
