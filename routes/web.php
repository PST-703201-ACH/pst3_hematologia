<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PersonaController;
use App\Http\Controllers\EstadoController;
use App\Http\Controllers\MunicipioController;
use App\Http\Controllers\ParroquiaController;
use App\Http\Controllers\RolController;
use App\Http\Controllers\Auth\LoginController;

Route::get('/', function () {
    return redirect()->route('login');
});

// Autenticación
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');
Route::post('/logout', [LoginController::class, 'logout']);

// Rutas protegidas
Route::middleware(['auth'])->group(function () {
    Route::get('/admin/', function () {
        return view('administrador.index');
    })->name('admin.index');

    Route::post('/usuario-guardar', [PersonaController::class, 'store'])->name('persona.store');
    
    Route::get('/obtener-estados', [EstadoController::class, 'getEstados'])->name('estados.json');
    Route::get('/obtener-municipios', [MunicipioController::class, 'getMunicipios'])->name('municipios.json');
    Route::get('/obtener-parroquias', [ParroquiaController::class, 'getParroquias'])->name('parroquias.json');
    Route::get('/obtener-roles', [RolController::class, 'getRoles'])->name('roles.json');
});