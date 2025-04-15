<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
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


Route::get('/', function () {
    return view('auth.login');
});

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
});

require __DIR__.'/auth.php';
