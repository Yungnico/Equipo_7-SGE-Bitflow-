<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
use App\Http\Controllers\Cliente\ClienteController;
use App\Http\Controllers\Cliente\ContactoController;

Route::prefix('clientes')->name('clientes.')->group(function () {
    
    // Rutas de clientes
    Route::get('/', [ClienteController::class, 'index'])->name('index');
    Route::get('/crear', [ClienteController::class, 'create'])->name('create');
    Route::post('/', [ClienteController::class, 'store'])->name('store');
    Route::get('/{cliente}/editar', [ClienteController::class, 'edit'])->name('edit');
    Route::put('/{cliente}', [ClienteController::class, 'update'])->name('update');
    Route::delete('/{cliente}', [ClienteController::class, 'destroy'])->name('destroy');

    // Rutas de contactos de un cliente
    Route::get('/{cliente}/contactos', [ContactoController::class, 'index'])->name('contactos.index');
    Route::post('/{cliente}/contactos', [ContactoController::class, 'store'])->name('contactos.store');
    Route::get('/{cliente}/contactos/{contacto}/editar', [ContactoController::class, 'edit'])->name('contactos.edit');
    Route::put('/{cliente}/contactos/{contacto}', [ContactoController::class, 'update'])->name('contactos.update');
    Route::delete('/{cliente}/contactos/{contacto}', [ContactoController::class, 'destroy'])->name('contactos.destroy');

});


Route::get('/contacto', [ContactoController::class, 'index']);

Route::resource('clientes', ClienteController::class);

// Rutas para contactos dentro de clientes
Route::prefix('clientes/{cliente}')->group(function () {
    Route::get('contactos', [ContactoController::class, 'index'])->name('clientes.contactos.index');
    Route::post('contactos', [ContactoController::class, 'store'])->name('clientes.contactos.store');
    Route::get('contactos/{contacto}/edit', [ContactoController::class, 'edit'])->name('clientes.contactos.edit');
    Route::put('contactos/{contacto}', [ContactoController::class, 'update'])->name('clientes.contactos.update');
    Route::delete('contactos/{contacto}', [ContactoController::class, 'destroy'])->name('clientes.contactos.destroy');
});


Route::get('/contactos/{id}/edit', [ContactoController::class, 'edit'])->name('contactos.edit');
Route::put('/contactos/{id}', [ContactoController::class, 'update'])->name('contactos.update');
Route::delete('/contactos/{id}', [ContactoController::class, 'destroy'])->name('contactos.destroy');

Route::resource('clientes.contactos', ContactoController::class);


Route::middleware(['auth'])->group(function () {
    Route::resource('clientes', ClienteController::class);
});

Route::resource('clientes', ClienteController::class);


Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/roles', function () {
    return view('roles');
})->middleware(['auth', 'verified']);

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
