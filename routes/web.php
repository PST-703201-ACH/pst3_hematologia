<?php
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\EnfermedadController;
use App\Http\Controllers\EstadoController;
use App\Http\Controllers\ExamenController;
use App\Http\Controllers\GerenteController;
use App\Http\Controllers\Medico\PacienteController as MedicoPacienteController;
use App\Http\Controllers\MunicipioController;
use App\Http\Controllers\ParroquiaController;
use App\Http\Controllers\PersonaController;
use App\Http\Controllers\PrimerIngresoController;
use App\Http\Controllers\RolController;
use App\Http\Controllers\TipoConsultaController;
use App\Http\Controllers\UsuarioController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// =========================================================================
// 1. RUTAS PÚBLICAS Y DE BIENVENIDA
// =========================================================================
Route::get('/', function () {
    return view('welcome');
});

// =========================================================================
// 2. AUTENTICACIÓN Y RECUPERACIÓN DE CONTRASEÑA
// =========================================================================
Route::controller(LoginController::class)->group(function () {
    Route::get('/login', 'showLoginForm')->name('login');
    Route::post('/login', 'login');
    Route::get('/logout', 'logout')->name('logout');
    Route::post('/logout', 'logout');
});

Route::controller(UsuarioController::class)->group(function () {
    Route::get('/olvide-clave/{username}', 'olvide_clave');
    Route::get('/nueva-clave/', 'nueva_clave');
    Route::get('/recuperar-clave/{token}', 'showResetForm')->name('password.reset');
    Route::post('/recuperar-clave', 'resetPassword')->name('password.update');
});

// =========================================================================
// 3. RUTAS PROTEGIDAS (SOLO PARA USUARIOS LOGUEADOS)
// =========================================================================
Route::middleware('auth')->group(function () {

// Enrutador inteligente del Dashboard central
Route::get('/dashboard', function () {
    /** @var User $user */
    $user = Auth::user();
    if (! $user instanceof User) abort(403);
    return redirect($user->getDashboardUrl());
})->name('dashboard');

// Módulos Compartidos (Primer ingreso, Enfermedades, Exámenes, Consultas)
Route::controller(PrimerIngresoController::class)->group(function () {
    Route::get('/primer-ingreso/completar-datos', 'show')->name('primer.ingreso.datos');
    Route::post('/primer-ingreso/completar-datos', 'guardarDatos')->name('primer.ingreso.guardar');
});

Route::post('/enfermedades', [EnfermedadController::class, 'store'])->name('enfermedad.store');
Route::get('/enfermedades', [EnfermedadController::class, 'index'])->name('enfermedad.index');

Route::post('/examenes', [ExamenController::class, 'store'])->name('examen.store');
Route::get('/examenes', [ExamenController::class, 'index'])->name('examen.index');

Route::post('/tipos-consulta', [TipoConsultaController::class, 'store'])->name('tipo_consulta.store');
Route::get('/tipos-consulta', [TipoConsultaController::class, 'index'])->name('tipo_consulta.index');

// ✨ CORRECCIÓN CRÍTICA: Rutas de carga de datos comunes (Disponibles para Admin y Médicos)
Route::get('/obtener-estados', [EstadoController::class, 'getEstados'])->name('estados.json');
Route::get('/obtener-municipios', [MunicipioController::class, 'getMunicipios'])->name('municipios.json');
Route::get('/obtener-parroquias', [ParroquiaController::class, 'getParroquias'])->name('parroquias.json');
Route::get('/obtener-roles', [RolController::class, 'getRoles'])->name('roles.json');


// ---------------------------------------------------------------------
    // 3.1. ROL: ADMINISTRADOR (role:1)
    // ---------------------------------------------------------------------
    Route::middleware('role:1')->prefix('admin')->group(function () {

        Route::get('/', function () {
            return view('administrador.index');
        })->name('admin.index');

        // Gestión de Personal / Personas
        Route::controller(PersonaController::class)->group(function () {
            Route::post('/usuario-guardar', 'registrar')->name('persona.registrar');
            Route::post('/usuario-actualizar', 'actualizar')->name('persona.actualizar');
        });

        // Gestión de Usuarios del Sistema
        Route::controller(UsuarioController::class)->group(function () {
            Route::get('/obtener-usuarios', 'listar');
            Route::get('/obtener-detalles/{id}', 'ver');
            Route::get('/precargar-usu/{id}', 'precargar');
            Route::get('/cambiar-status/{id}', 'nuevo_status');
        });

        Route::get('/dashboard-admin', [GerenteController::class, 'dashboards']);
    });
// ---------------------------------------------------------------------
    // 3.2. ROL: MÉDICO (role:2)
    // ---------------------------------------------------------------------
    Route::middleware('role:2')->prefix('medico')->group(function () {

        Route::get('/', function () {
            $pacientes = \App\Models\Paciente::with('persona')->get();
            $estados = \App\Models\Estado::all();
            $representantes = collect();

            return view('medico.index', compact('pacientes', 'estados', 'representantes'));
        })->name('medico.index');

        // Gestión de Pacientes por el Médico
        Route::controller(MedicoPacienteController::class)->group(function () {
            Route::get('/pacientes', 'index')->name('medico.pacientes.index');
            Route::get('/pacientes/crear', 'create')->name('medico.pacientes.create');
            Route::post('/pacientes/crear', 'store')->name('medico.pacientes.store');
            Route::get('/pacientes/{id}', 'show')->name('medico.pacientes.show');
            Route::delete('/pacientes/{id}', 'destroy')->name('medico.pacientes.destroy');

            Route::get('/obtener-pacientes', 'listar')->name('medico.pacientes.listar');
            Route::get('/representantes/buscar', 'getRepresentantes')->name('medico.representantes.buscar');
        });
    });

/*
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
*/


});