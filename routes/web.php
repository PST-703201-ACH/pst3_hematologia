<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PersonaController;
use App\Http\Controllers\EstadoController;
use App\Http\Controllers\MunicipioController;
use App\Http\Controllers\ParroquiaController;
use App\Http\Controllers\RolController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Medico\PacienteController as MedicoPacienteController;

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

    Route::get('/medico/', function () {
        return view('medico.dashboard');
    })->name('medico.index');

    Route::get('/medico/pacientes/crear', [MedicoPacienteController::class, 'create'])->name('medico.pacientes.create');
    Route::post('/medico/pacientes/crear', [MedicoPacienteController::class, 'store'])->name('medico.pacientes.store');
    Route::get('/medico/pacientes', [MedicoPacienteController::class, 'index'])->name('medico.pacientes.index');
    Route::delete('/medico/pacientes/{id}', [MedicoPacienteController::class, 'destroy'])->name('medico.pacientes.destroy');

    Route::post('/usuario-guardar', [PersonaController::class, 'store'])->name('persona.store');
    
    Route::get('/obtener-estados', [EstadoController::class, 'getEstados'])->name('estados.json');
    Route::get('/obtener-municipios', [MunicipioController::class, 'getMunicipios'])->name('municipios.json');
    Route::get('/obtener-parroquias', [ParroquiaController::class, 'getParroquias'])->name('parroquias.json');
    Route::get('/obtener-roles', [RolController::class, 'getRoles'])->name('roles.json');
});