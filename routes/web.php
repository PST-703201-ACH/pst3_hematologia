<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PersonaController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\EstadoController;
use App\Http\Controllers\MunicipioController;
use App\Http\Controllers\ParroquiaController;
use App\Http\Controllers\RolController;
use App\Http\Controllers\GerenteController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Medico\PacienteController;
use App\Http\Controllers\CitaController;
use App\Http\Controllers\ProtocoloTratamientoController;

// Autenticación
Route::get('/', [LoginController::class, 'showLoginForm'])->name('login');
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');
Route::post('/logout', [LoginController::class, 'logout']);
Route::get('/olvide-clave/{username}', [UsuarioController::class, 'olvide_clave']);
Route::get('/nueva-clave/', [UsuarioController::class, 'nueva_clave']);


// Rutas protegidas
    Route::get('/admin/', function () {
        return view('administrador.index');
    })->name('admin.index');

    Route::get('/medico/', function () {
        return view('medico.index');
    })->name('medico.index');

    Route::get('/admvo/', function () {
        return view('administrativo.index');
    })->name('admvo.index');

//Gerente
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
Route::get('/obtener-auditoria', [UsuarioController::class, 'auditar']);


//Medico

//ARREGLANDO
Route::delete('/medico/pacientes/{id}', [PacienteController::class, 'destroy'])->name('medico.pacientes.destroy');
Route::post('/medico/pacientes/crear', [PacienteController::class, 'store'])->name('medico.pacientes.store');

//ARREGLADO
Route::get('/obtener-pacientes', [PacienteController::class, 'listar']);

//Administrativo
Route::post('/cita-agendar', [CitaController::class, 'agendar'])->name('cita.agendar');
Route::get('/obtener-citas', [CitaController::class, 'getCitas'])->name('citas.json');
Route::get('/precargar-cita/{id}', [CitaController::class, 'precargar']);
Route::post('/cita-rep', [CitaController::class, 'reprogramar'])->name('cita.rep');



// Rutas temporales de desarrollo aislado - Módulos de Tratamientos y Laboratorios
Route::middleware(['auth'])->prefix('sandbox-mis-modulos')->group(function () {
    
    // Rutas para Soporte Transfusional (Médico/Enfermera)
    Route::get('transfusiones', [App\Http\Controllers\TransfusionController::class, 'index']);
    Route::post('transfusiones', [App\Http\Controllers\TransfusionController::class, 'store']);
    Route::get('transfusiones/{id}', [App\Http\Controllers\TransfusionController::class, 'show']);
    Route::put('transfusiones/{id}', [App\Http\Controllers\TransfusionController::class, 'update']);

    // Rutas para Resultados de Laboratorio (Médico/Enfermera/Administrativo)
    Route::get('examenes', [App\Http\Controllers\ExamenController::class, 'index']);
    Route::post('examenes', [App\Http\Controllers\ExamenController::class, 'store']);
    Route::get('examenes/{id}', [App\Http\Controllers\ExamenController::class, 'show']);
    Route::put('examenes/{id}', [App\Http\Controllers\ExamenController::class, 'update']);

    use App\Http\Controllers\TransfusionHemocomponenteController;


         
    Route::resource('transfusiones', TransfusionHemocomponenteController::class)
         ->except(['create', 'edit', 'destroy']);

use App\Http\Controllers\ProtocoloTratamientoController;

Route::middleware(['auth', 'role:medico|enfermera'])->group(function () {
    
    // Rutas estándar para el CRUD (Listar, Crear, Consultar, Actualizar)
    Route::resource('protocolos', ProtocoloTratamientoController::class);

    // Nuestra ruta especial para reprogramar la semana
    Route::post('/protocolos/sesiones/{sesion_id}/reprogramar', [ProtocoloTratamientoController::class, 'reprogramarSemana'])
         ->name('protocolos.sesiones.reprogramar');
});
