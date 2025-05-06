<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Cliente\ClienteController;
use App\Http\Controllers\Cliente\ContactoClienteController;
use App\Http\Controllers\ServicioController;
use App\Http\Controllers\CrudUserController;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\CotizacionController;
use App\Models\Cotizacion;
use Barryvdh\DomPDF\Facade\Pdf;


// Ruta para VER el PDF en el navegador
Route::get('/cotizaciones/{id}/preparar-pdf', [CotizacionController::class, 'prepararPDF'])->name('cotizaciones.prepararPDF');
Route::get('/cotizaciones/{id}/pdf', [CotizacionController::class, 'generarPDF']);

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

Route::get('/cotizaciones/create', [CotizacionController::class, 'create'])->name('cotizaciones.create');

Route::middleware('auth')->group(function () {
    Route::resource('cotizaciones', CotizacionController::class);
    Route::get('/cotizaciones/{id}/info', [CotizacionController::class, 'getCotizacion'])->name('cotizaciones.info');
});

Route::middleware('auth')->group(function () {

    // Rutas de Clientes
    Route::resource('clientes', ClienteController::class);
    Route::get('/clientes/{id}/info', [ClienteController::class, 'getCliente'])->name('clientes.info');

    // Rutas de contactos anidados bajo clientes
    Route::prefix('clientes/{cliente}')->name('clientes.')->group(function () {
        Route::get('contactos/create', [ContactoClienteController::class, 'create'])->name('contactos.create');
        Route::post('contactos', [ContactoClienteController::class, 'store'])->name('contactos.store');
        Route::resource('contactos', ContactoClienteController::class)->except(['create', 'show', 'store']);
        Route::get('contactos/info', [ContactoClienteController::class, 'getContactos'])->name('contactos.info');
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

});

// Página de bienvenida

Route::get('/', function () {
    return view('auth.login');
});

require __DIR__.'/auth.php';
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');
//Cambios en el perfil de la persona logeada
Route::middleware(['auth'])->group(function () {
    Route::get('/perfil', function () {
        return view('profile.edit', [
            'user' => Auth::user()
        ]);
    })->name('profile.show');

    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
});
//Cambio de contraseñas
Route::middleware(['auth'])->group(function () {
    Route::get('/cambiar-password', function () {
        return view('password.change');
    })->name('password.change');

    Route::put('/password', [ProfileController::class, 'updatePassword'])->name('password.update');
});
//Registro de usuarios
Route::middleware(['auth'])->group(function () {
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
});

Route::get('/roles', function () {
    return view('roles');
})->middleware(['auth', 'verified']);


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    #SERVICIOS
    Route::get('/servicios/crear', [ServicioController::class, 'create'])->name('servicios.create');
    Route::post('/servicios', [ServicioController::class, 'store'])->name('servicios.store');
    Route::get('/servicios/{id}/editar', [ServicioController::class, 'edit'])->name('servicios.edit');
    Route::put('/servicios/{id}', [ServicioController::class, 'update'])->name('servicios.update');
    Route::get('/servicios', [ServicioController::class, 'index'])->name('servicios.index');
    Route::put('/servicios/{id}/toggle', [ServicioController::class, 'toggleEstado'])->name('servicios.toggleEstado');
    Route::delete('/servicios/{id}', [ServicioController::class, 'destroy'])->name('servicios.destroy');
    Route::get('/servicios/{id}/info', [ServicioController::class, 'getServicio'])->name('servicios.info');

});


Route::resource('viewusers', CrudUserController::class)->names('viewusers');

require __DIR__ . '/auth.php';
