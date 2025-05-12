<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CrudUserController;
use App\Http\Controllers\ServicioController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\MonedaController;
use App\Http\Controllers\CotizacionController;
use App\Http\Controllers\ClienteExportController;
use App\Http\Controllers\Cliente\ClienteController;
use App\Http\Controllers\Cliente\ContactoClienteController;

// Página de inicio
Route::get('/', function () {
    return view('auth.login');
});

// Rutas de autenticación
require __DIR__ . '/auth.php';

// Rutas protegidas por middleware auth
Route::middleware(['auth'])->group(function () {

    // Dashboard
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->middleware('verified')->name('dashboard');

    // Perfil
    Route::get('/perfil', function () {
        return view('profile.edit', [
            'user' => Auth::user()
        ]);
    })->name('profile.show');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Cambio de contraseña
    Route::get('/cambiar-password', function () {
        return view('password.change');
    })->name('password.change');
    Route::put('/password', [ProfileController::class, 'updatePassword'])->name('password.update');

    // Registro de usuarios
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');

    // Clientes
    Route::resource('clientes', ClienteController::class);
    Route::get('/clientes/{id}/info', [ClienteController::class, 'getCliente'])->name('clientes.info');
    Route::get('/clientes/buscar', [ClienteController::class, 'buscar'])->name('clientes.buscar');
    Route::get('/clientes/resultados', [ClienteController::class, 'buscar']);

    // Contactos de clientes (anidados)
    Route::prefix('clientes/{cliente}')->name('clientes.')->group(function () {
        Route::get('contactos/create', [ContactoClienteController::class, 'create'])->name('contactos.create');
        Route::post('contactos', [ContactoClienteController::class, 'store'])->name('contactos.store');
        Route::resource('contactos', ContactoClienteController::class)->except(['create', 'show', 'store']);
        Route::get('contactos/info', [ContactoClienteController::class, 'getContactos'])->name('contactos.info');
    });

    // Contactos independientes
    Route::get('/contactos/{contacto}/edit', [ContactoClienteController::class, 'edit'])->name('contactos.edit');
    Route::put('/contactos/{contacto}', [ContactoClienteController::class, 'update'])->name('contactos.update');
    Route::resource('clientes.contactos', ContactoClienteController::class)->except(['show']);

    // Cotizaciones
    Route::get('/cotizaciones/borrador', [CotizacionController::class, 'showBorrador'])->name('cotizaciones.borrador');
    Route::get('/cotizaciones/create', [CotizacionController::class, 'create'])->name('cotizaciones.create');
    Route::get('/cotizaciones/{id}/edit', [CotizacionController::class, 'edit'])->name('cotizaciones.edit');
    Route::put('/cotizaciones/{id}', [CotizacionController::class, 'update'])->name('cotizaciones.editarestado');
    Route::get('/cotizaciones/{id}/info', [CotizacionController::class, 'getCotizacion'])->name('cotizaciones.info');

    // PDF y correo
    Route::get('/cotizaciones/{id}/preparar-pdf', [CotizacionController::class, 'prepararPDF'])->name('cotizaciones.prepararPDF');
    Route::post('/cotizaciones/{id}/enviar', [CotizacionController::class, 'enviarCorreo'])->name('cotizaciones.enviar');
    Route::get('/cotizaciones/{id}/Email', [CotizacionController::class, 'prepararEmail'])->name('cotizaciones.prepararEmail');
    // Generar PDF de cotizaciones
    Route::post('/cotizaciones/{id}/pdf', [CotizacionController::class, 'generarPDF'])->name('cotizaciones.generarPDFobservaciones');
    Route::get('/cotizaciones/{id}/pdf', [CotizacionController::class, 'generarPDF'])->name('cotizaciones.generarPDF');

    Route::resource('cotizaciones', CotizacionController::class)->except(['edit']);

    // Servicios
    Route::get('/servicios', [ServicioController::class, 'index'])->name('servicios.index');
    Route::get('/servicios/crear', [ServicioController::class, 'create'])->name('servicios.create');
    Route::post('/servicios', [ServicioController::class, 'store'])->name('servicios.store');
    Route::get('/servicios/{id}/editar', [ServicioController::class, 'edit'])->name('servicios.edit');
    Route::put('/servicios/{id}', [ServicioController::class, 'update'])->name('servicios.update');
    Route::put('/servicios/{id}/toggle', [ServicioController::class, 'toggleEstado'])->name('servicios.toggleEstado');
    Route::delete('/servicios/{id}', [ServicioController::class, 'destroy'])->name('servicios.destroy');
    Route::get('/servicios/{id}/info', [ServicioController::class, 'getServicio'])->name('servicios.info');

    // Categorías
    Route::get('/categorias', [CategoriaController::class, 'index'])->name('categorias.index');
    Route::post('/categorias', [CategoriaController::class, 'store'])->name('categorias.store');
    Route::get('/categorias/{categoria}/edit', [CategoriaController::class, 'edit'])->name('categorias.edit');
    Route::put('/categorias/{categoria}', [CategoriaController::class, 'update'])->name('categorias.update');
    Route::delete('/categorias/{categoria}', [CategoriaController::class, 'destroy'])->name('categorias.destroy');

    // Monedas
    Route::resource('monedas', MonedaController::class)->only(['index', 'store', 'update', 'destroy']);

    // Vista y CRUD de usuarios 
    Route::resource('viewusers', CrudUserController::class)->names('viewusers');
    Route::get('/viewusers', [CrudUserController::class, 'index'])->name('viewusers.index');

});
Route::get('clientes/exportar', [ClienteController::class, 'exportar'])->name('clientes.exportar');
// Vista de roles
Route::get('/roles', function () {
    return view('roles');
})->middleware(['auth', 'verified']);

