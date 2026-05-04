<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PersonaController;
use App\Http\Controllers\EstadoController;
use App\Http\Controllers\MunicipioController;
use App\Http\Controllers\ParroquiaController;
use App\Http\Controllers\RolController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\GerenteController;


Route::get('/', function () {
    return view('welcome');
});
Route::get('/admin/', function () {
    return view('administrador.index');
});

Route::post('/usuario-guardar', [PersonaController::class, 'registrar'])->name('persona.registrar');
Route::post('/usuario-actualizar', [PersonaController::class, 'actualizar'])->name('persona.actualizar');

Route::get('/obtener-estados', [EstadoController::class, 'getEstados'])->name('estados.json');
Route::get('/obtener-municipios', [MunicipioController::class, 'getMunicipios'])->name('municipios.json');
Route::get('/obtener-parroquias', [ParroquiaController::class, 'getParroquias'])->name('parroquias.json');
Route::get('/obtener-roles', [RolController::class, 'getRoles'])->name('roles.json');
Route::get('/obtener-usuarios', [UsuarioController::class, 'listar']);
Route::get('/dashboard-admin', [GerenteController::class, 'dashboards']);
Route::get('/obtener-detalles/{id}', [UsuarioController::class, 'ver']);
Route::get('/precargar-usu/{id}', [UsuarioController::class, 'precargar']);
Route::get('/cambiar-status/{id}', [UsuarioController::class, 'nuevo_status']);

