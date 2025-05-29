<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Cliente\ClienteController;
use App\Http\Controllers\Cliente\ContactoClienteController;
use App\Http\Controllers\ServicioController;
use App\Http\Controllers\CrudUserController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\MonedaController;
use App\Http\Controllers\CotizacionController;
use App\Models\Cotizacion;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ClienteExportController;
use App\Http\Controllers\TransferenciaController;
use App\Http\Controllers\FacturacionController;

Route::get('/facturacion/upload', function () {
    return view('facturacion.upload');
})->name('facturacion.upload');

Route::post('/facturacion/importar', [FacturacionController::class, 'importar'])->name('facturacion.importar');
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
//rutas de facturacion
route::middleware('auth')->group(function () {
    Route::get('/facturacion', [FacturacionController::class, 'index'])->name('facturacion.index');
});


Route::middleware('auth')->group(function () {
    Route::resource('cotizaciones', CotizacionController::class)->except(['edit', 'show']);
    Route::get('/cotizaciones/{id}/info', [CotizacionController::class, 'getCotizacion'])->name('cotizaciones.info');
    Route::get('/cotizaciones/{id}/Email', [CotizacionController::class, 'prepararEmail'])->name('cotizaciones.prepararEmail');
    Route::get('/cotizaciones/create', [CotizacionController::class, 'create'])->name('cotizaciones.create');

    Route::get('/cotizaciones/borrador', [CotizacionController::class, 'showBorrador'])->name('cotizaciones.borrador');
});
//RUTAS DE PARIDAD
use App\Http\Controllers\ParidadController;


//Route::resource('paridades', ParidadController::class)->except(['create', 'store', 'destroy', 'show']);
Route::get('/paridades', [ParidadController::class, 'index'])->name('paridades.index');
Route::get('/paridades/fetch', [ParidadController::class, 'fetchFromAPI'])->name('paridades.fetch');
Route::get('/paridades/{paridad}/edit', [ParidadController::class, 'edit'])->name('paridades.edit');
Route::put('/paridades/{paridad}', [ParidadController::class, 'update'])->name('paridades.update');
Route::get('/paridades/recordatorio', [ParidadController::class, 'checkRecordatorioAnual'])->name('paridades.recordatorio');
Route::get('/paridades/{paridad}/edit', [ParidadController::class, 'edit'])->name('paridades.edit');
Route::put('/paridades/{paridad}', [ParidadController::class, 'update'])->name('paridades.update');

//rutas de clientes
Route::middleware('auth')->group(function () {

    Route::get('clientes/exportar', [ClienteController::class, 'exportar'])->name('clientes.exportar');

    Route::get('/contactos/{contacto}/edit', [ContactoClienteController::class, 'edit'])->name('contactos.edit');
    Route::put('/contactos/{contacto}', [ContactoClienteController::class, 'update'])->name('contactos.update');

    Route::resource('clientes.contactos', ContactoClienteController::class)->except(['show']);


    Route::get('/clientes/buscar', [ClienteController::class, 'buscar'])->name('clientes.buscar');
    Route::get('/clientes/resultados', [ClienteController::class, 'buscar']);

    // Ruta para VER el PDF en el navegador
    Route::post('/cotizaciones/{id}/pdf', [CotizacionController::class, 'generarPDF'])->name('cotizaciones.generarPDFobservaciones');
    Route::get('/cotizaciones/{id}/pdf', [CotizacionController::class, 'generarPDF'])->name('cotizaciones.generarPDF');
    Route::get('/cotizaciones/{id}/preparar-pdf', [CotizacionController::class, 'prepararPDF'])->name('cotizaciones.prepararPDF')->middleware('auth');
    Route::post('cotizaciones/{id}/enviar', [CotizacionController::class, 'enviarCorreo'])->name('cotizaciones.enviar')->middleware('auth');
    Route::get('/cotizaciones/{id}/Email', [CotizacionController::class, 'prepararEmail'])->name('cotizaciones.prepararEmail');
    Route::get('/cotizaciones/{id}/edit', [CotizacionController::class, 'edit'])->name('cotizaciones.edit');
    Route::put('/cotizaciones/{id}', [CotizacionController::class, 'update'])->name('cotizaciones.editarestado');
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
    #CATEGORIAS
    Route::get('/categorias', [CategoriaController::class, 'index'])->name('categorias.index');
    Route::post('/categorias', [CategoriaController::class, 'store'])->name('categorias.store');
    Route::get('/categorias/{categoria}/edit', [CategoriaController::class, 'edit'])->name('categorias.edit');
    Route::put('/categorias/{categoria}', [CategoriaController::class, 'update'])->name('categorias.update');
    Route::delete('/categorias/{categoria}', [CategoriaController::class, 'destroy'])->name('categorias.destroy');

    Route::resource('monedas', MonedaController::class)->only(['index', 'store', 'update', 'destroy']);
    Route::resource('viewusers', CrudUserController::class)->names('viewusers');
    Route::get('/viewusers', [CrudUserController::class, 'index'])->name('viewusers.index');

    #cuenta corrienete
    Route::get('/transferencias', [TransferenciaController::class, 'index'])->name('transferencias.index');
    Route::post('/transferencias/importar', [TransferenciaController::class, 'importarExcel'])->name('transferencias.importar');
    Route::post('/transferencias', [TransferenciaController::class, 'store'])->name('transferencias.store');



    //Cambio de contraseñas
    Route::get('/cambiar-password', function () {
        return view('password.change');
    })->name('password.change');

    Route::put('/password', [ProfileController::class, 'updatePassword'])->name('password.update');
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

require __DIR__ . '/auth.php';
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

//Registro de usuarios
Route::middleware(['auth'])->group(function () {
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
});

Route::get('/roles', function () {
    return view('roles');
})->middleware(['auth', 'verified']);
require __DIR__ . '/auth.php';
