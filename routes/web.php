<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PersonaController;

Route::get('/', function () {
    return view('welcome');
});

// 🚀 Tus nuevas rutas orientadas a objetos van aquí abajo:
Route::post('/pacientes', [PersonaController::class, 'store']);
Route::get('/pacientes/{cedula}', [PersonaController::class, 'showByCedula']);
use App\Http\Controllers\ExamenController;
Route::get('/examenes', [ExamenController::class, 'index']);
Route::post('/examenes', [ExamenController::class, 'store']);
use App\Http\Controllers\EnfermedadController;
Route::get('/enfermedades', [EnfermedadController::class, 'index']);
Route::post('/enfermedades', [EnfermedadController::class, 'store']);