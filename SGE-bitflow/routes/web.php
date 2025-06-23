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
use App\Http\Controllers\CostoController;
use App\Http\Controllers\SubcategoriaController;


Route::get('/facturas/por-cliente', [FacturacionController::class, 'graficoPorCliente']);
Route::get('/facturas/comparativo-anual', [FacturacionController::class, 'comparativoAnual']);
Route::get('/facturas/facturado-vs-ingresos', [FacturacionController::class, 'facturadoVsIngresos']);
Route::get('/facturas/kpi', [FacturacionController::class, 'kpi']);

Route::get('/cotizaciones/kpi', [CotizacionController::class, 'getCotizacionesKpi'])->name('cotizaciones.kpi');

Route::get('/facturacion/upload', function () {
    return view('facturacion.upload');
})->name('facturacion.upload');
Route::resource('facturacion', FacturacionController::class)->except(['create', 'edit', 'show', 'store']);
Route::post('/facturacion/importar', [FacturacionController::class, 'importar'])->name('facturacion.importar');
route::post('/facturacion/crear', [FacturacionController::class, 'store'])->name('facturacion.store');
Route::put('/facturas/{id}/cambiar-estado', [FacturacionController::class, 'cambiarEstado'])->name('facturas.cambiarEstado');

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
    Route::get('/facturacion', [FacturacionController::class, 'index'])->middleware('can:factura.index')->name('facturacion.index');
});


Route::middleware('auth')->group(function () {
    Route::resource('cotizaciones', CotizacionController::class)->middleware('can:cotizaciones.index')->except(['edit', 'show']); //middleware puesto
    Route::get('/cotizaciones/{id}/info', [CotizacionController::class, 'getCotizacion'])->middleware('can:cotizaciones.info')->name('cotizaciones.info'); //middleware puesto
    Route::get('/cotizaciones/create', [CotizacionController::class, 'create'])->middleware('can:cotizaciones.create')->name('cotizaciones.create'); //middleware puesto
    Route::get('/cotizaciones/borrador', [CotizacionController::class, 'showBorrador'])->middleware('can:cotizaciones.borrador')->name('cotizaciones.borrador'); //middleware puesto
    Route::get('/cotizacion/conciliar/{id}', [CotizacionController::class, 'conciliar'])->name('cotizacion.conciliar');
});


//PARIDADES OFICIAL 
use App\Http\Controllers\ParidadController;
use App\Models\Facturacion;

//Route::resource('paridades', ParidadController::class)->except(['create', 'store', 'destroy', 'show']);
Route::get('/paridades', [ParidadController::class, 'index'])->middleware('can:paridades.index')->name('paridades.index');
Route::get('/paridades/fetch', [ParidadController::class, 'fetchFromAPI'])->middleware('can:paridades.fetch')->name('paridades.fetch'); //middleware puesto
Route::get('/paridades/{paridad}/edit', [ParidadController::class, 'edit'])->middleware('can:paridades.edit')->name('paridades.edit'); //middleware puesto
Route::put('/paridades/{paridad}', [ParidadController::class, 'update'])->name('paridades.update'); //no necesita middleware
Route::get('/paridades/recordatorio', [ParidadController::class, 'checkRecordatorioAnual'])->name('paridades.recordatorio'); //no necesita middleware
Route::put('/paridades/{paridad}', [ParidadController::class, 'update'])->name('paridades.update'); //no necesita middleware
Route::post('/paridades', [ParidadController::class, 'store'])->name('paridades.store');
Route::delete('/paridades/{paridad}', [ParidadController::class, 'destroy'])->name('paridades.destroy');

//rutas de clientes
Route::middleware('auth')->group(function () {

    Route::get('clientes/exportar', [ClienteController::class, 'exportar'])->name('clientes.exportar'); //no necesita middleware

    Route::get('/contactos/{contacto}/edit', [ContactoClienteController::class, 'edit'])->middleware('can:contactoCliente.edit')->name('contactos.edit'); //middleware puesto
    Route::put('/contactos/{contacto}', [ContactoClienteController::class, 'update'])->name('contactos.update'); //no necesita middleware

    Route::resource('clientes.contactos', ContactoClienteController::class)->except(['show']);


    Route::get('/clientes/buscar', [ClienteController::class, 'buscar'])->middleware('can:contactoCliente.buscar')->name('clientes.buscar'); //middleware puesto
    Route::get('/clientes/resultados', [ClienteController::class, 'buscar'])->middleware('can:contactoCliente.resultados'); //middleware puesto

    // Ruta para VER el PDF en el navegador
    Route::post('/cotizaciones/{id}/pdf', [CotizacionController::class, 'generarPDF'])->name('cotizaciones.generarPDFobservaciones');
    Route::get('/cotizaciones/{id}/pdf', [CotizacionController::class, 'generarPDF'])->name('cotizaciones.generarPDF');
    Route::get('/cotizaciones/{id}/preparar-pdf', [CotizacionController::class, 'prepararPDF'])->middleware('can:cotizaciones.prepararpdf')->name('cotizaciones.prepararPDF')->middleware('auth'); //middleware puesto
    Route::post('cotizaciones/{id}/enviar', [CotizacionController::class, 'enviarCorreo'])->name('cotizaciones.enviar')->middleware('auth');
    Route::get('/cotizaciones/{id}/Email', [CotizacionController::class, 'prepararEmail'])->middleware('can:cotizaciones.email')->name('cotizaciones.prepararEmail'); //middleware puesto
    Route::get('/cotizaciones/{id}/edit', [CotizacionController::class, 'edit'])->middleware('can:cotizaciones.edit')->name('cotizaciones.edit'); //middleware puesto
    Route::put('/cotizaciones/{id}', [CotizacionController::class, 'update'])->name('cotizaciones.editarestado');

    #perfil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    #SERVICIOS
    Route::get('/servicios/crear', [ServicioController::class, 'create'])->name('servicios.create');
    Route::post('/servicios', [ServicioController::class, 'store'])->name('servicios.store');
    Route::get('/servicios/{id}/editar', [ServicioController::class, 'edit'])->name('servicios.edit');
    Route::put('/servicios/{id}', [ServicioController::class, 'update'])->name('servicios.update');
    Route::get('/servicios', [ServicioController::class, 'index'])->middleware('can:servicios.index')->name('servicios.index'); //middleware puesto
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


    Route::resource('viewusers', CrudUserController::class)->middleware('can:admin.viewusers.index')->names('viewusers'); //tiene permisos
    Route::get('/viewusers', [CrudUserController::class, 'index'])->middleware('can:admin.viewusers.index')->name('viewusers.index'); //tiene permisos

    #cuenta corrienete
    Route::get('/transferencias', [TransferenciaController::class, 'index'])->middleware('can:factura.index')->name('transferencias.index');
    Route::post('/transferencias/importar', [TransferenciaController::class, 'importarExcel'])->name('transferencias.importar');
    Route::post('/transferencias', [TransferenciaController::class, 'store'])->name('transferencias.store');
    Route::get('/transferencias/conciliar', [TransferenciaController::class, 'conciliarTransferencias'])->name('transferencias.conciliar');
    Route::post('/transferencias/conciliar-manual', [TransferenciaController::class, 'conciliarManual'])->name('transferencias.conciliar.manual');

    # módulo costos
    Route::get('/costos', [CostoController::class, 'index'])->middleware('can:factura.index')->name('costos.index');
    Route::get('/costos/crear', [CostoController::class, 'create'])->name('costos.create');
    Route::post('/costos', [CostoController::class, 'store'])->name('costos.store');
    Route::get('/costos/{costo}/editar', [CostoController::class, 'edit'])->name('costos.edit');
    Route::put('/costos/{costo}', [CostoController::class, 'update'])->name('costos.update');
    Route::delete('/costos/{costo}', [CostoController::class, 'destroy'])->name('costos.destroy');
    Route::get('/subcategorias/{categoriaId}', [SubcategoriaController::class, 'getPorCategoria']);
    Route::post('/transferencias/conciliar-egreso', [TransferenciaController::class, 'conciliarEgreso'])->name('transferencias.conciliar.egreso');




    //Cambio de contraseñas
    #Route::get('/cambiar-password', function () {
    #    return view('password.change');
    #})->name('password.change');
    #Route::put('/password', [ProfileController::class, 'updatePassword'])->name('password.update');
    Route::put('/clientes/{id}', [ClienteController::class, 'update'])->name('clientes.update');
    Route::get('/dashboard/buenos-clientes', [FacturacionController::class, 'rankingBuenosClientesAjax']);

    Route::resource('clientes', ClienteController::class)->except(['update']); //middleware puesto

    // Rutas de contactos anidados bajo clientes
    Route::prefix('clientes/{cliente}')->name('clientes.')->group(function () {
        Route::get('contactos/create', [ContactoClienteController::class, 'create'])->middleware('can:contactoCliente.create')->name('contactos.create'); //middleware puesto
        Route::post('contactos', [ContactoClienteController::class, 'store'])->name('contactos.store');
        Route::resource('contactos', ContactoClienteController::class)->middleware('can:cliente.contacto')->except(['create', 'show', 'store']); //middleware puesto
        Route::get('contactos/info', [ContactoClienteController::class, 'getContactos'])->name('contactos.info');
    });
    // Rutas de Clientes
    
    Route::get('/clientes/{id}/info', [ClienteController::class, 'getCliente'])->middleware('can:cliente.info')->name('clientes.info'); //middleware puesto


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
    Route::get('/users/create', [UserController::class, 'create'])->middleware('can:user.create')->name('users.create');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
});

Route::get('/roles', function () {
    return view('roles');
})->middleware(['auth', 'verified']);
require __DIR__ . '/auth.php';
