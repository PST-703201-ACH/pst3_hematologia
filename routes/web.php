<?php
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ConsultaController;
use App\Http\Controllers\CitaController;
use App\Http\Controllers\EnfermeriaController;
use App\Http\Controllers\EnfermedadController;
use App\Http\Controllers\EstadoController;
use App\Http\Controllers\GerenteController;
use App\Http\Controllers\AdmvoController;
use App\Http\Controllers\MedicoController;
use App\Http\Controllers\MunicipioController;
use App\Http\Controllers\ParroquiaController;
use App\Http\Controllers\PersonaController;
use App\Http\Controllers\RolController;
use App\Http\Controllers\TratamientoController;
use App\Http\Controllers\UsuarioController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// =========================================================================
// RUTAS PÚBLICAS Y DE BIENVENIDA
// =========================================================================
Route::get('/', function () {
    return view('auth.portal');
});

// =========================================================================
// AUTENTICACIÓN Y RECUPERACIÓN DE CONTRASEÑA
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
// RUTAS PROTEGIDAS (SOLO PARA USUARIOS LOGUEADOS)
// =========================================================================
Route::middleware('auth')->group(function () {

// Enrutador inteligente del Dashboard central
Route::get('/dashboard', function () {
    /** @var User $user */
    $user = Auth::user();
    if (! $user instanceof User) abort(403);
    return redirect($user->getDashboardUrl());
})->name('dashboard');

Route::get('/obtener-estados', [EstadoController::class, 'getEstados'])->name('estados.json');
Route::get('/obtener-municipios', [MunicipioController::class, 'getMunicipios'])->name('municipios.json');
Route::get('/obtener-parroquias', [ParroquiaController::class, 'getParroquias'])->name('parroquias.json');
Route::get('/obtener-roles', [RolController::class, 'getRoles'])->name('roles.json');


// ---------------------------------------------------------------------
    // ADMINISTRADOR
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

        // Gestión de Usuarios
        Route::controller(UsuarioController::class)->group(function () {
            Route::get('/obtener-usuarios', 'listar');
            Route::get('/obtener-detalles/{id}', 'ver');
            Route::get('/precargar-usu/{id}', 'precargar');
            Route::get('/cambiar-status/{id}', 'nuevo_status');
            Route::get('/obtener-enfermedades', 'listarEnf');
            Route::get('/obtener-auditoria', 'auditar');
            Route::get('/obtener-reportes', 'auditarPdf');
            // El respaldo queda restringido al middleware role:1 de este grupo administrativo.
            Route::post('/respaldar-base-datos', 'respaldarBaseDatos')->name('admin.backup.database');
        });

        Route::controller(GerenteController::class)->group(function () {
            Route::get('/dashboard-admin', 'dashboards');
            Route::get('/obtener-medicinas-disponibles', 'listarMedDis');
            Route::get('/obtener-protocolos', 'listarProt');
            Route::get('/obtener-protocolo-fases/{id}', 'verFases');
            Route::post('/protocolo-guardar', 'registrarProt')->name('protocolo.registrar');

            Route::get('/obtener-enfermedades', 'listarEnf');
            Route::get('/precargar-enf/{id}', 'precargarEnf');
            Route::post('/enfermedad-guardar', 'registrarEnf')->name('enfermedad.registrar');
            Route::post('/enfermedad-actualizar', 'actualizarEnf')->name('enfermedad.actualizar');

            Route::get('/obtener-medicinas', 'listarMed');
            Route::get('/precargar-med/{id}', 'precargarMed');
            Route::post('/medicina-guardar', 'registrarMed')->name('medicina.registrar');
            Route::post('/medicina-actualizar', 'actualizarMed')->name('medicina.actualizar');
        });
    });

    // ADMINISTRATIVO
    Route::middleware('role:2')->prefix('admvo')->group(function () {
        Route::get('/', function () {
            return view('administrativo.index');
        })->name('admvo.index');

        Route::controller(AdmvoController::class)->group(function () {
            Route::get('/dashboard-admvo', 'dashboards');
        });

        Route::controller(CitaController::class)->group(function () {
            Route::post('/cita-agendar', 'agendar')->name('cita.agendar');
            Route::get('/obtener-citas', 'listarCitas')->name('citas.json');
            Route::get('/citas/exportar/pdf', 'exportarPdf')->name('citas.exportar.pdf');
            Route::get('/citas/exportar/excel', 'exportarExcel')->name('citas.exportar.excel');
            Route::get('/precargar-cita/{id}', 'precargar');
            Route::post('/cita-rep', 'reprogramar')->name('cita.rep');
        });
    });

// ---------------------------------------------------------------------
    // MÉDICO
// ---------------------------------------------------------------------
Route::middleware('role:3')->prefix('medico')->group(function () {

    Route::get('/', function (\Illuminate\Http\Request $request) {
                $pacientes = \App\Models\Paciente::with('persona')->get();
                $estados = \App\Models\Estado::all();
                $representantes = collect();
                $vistaInicial = $request->query('vista');

                return view('medico.index', compact('pacientes', 'estados', 'representantes', 'vistaInicial'));
    })->name('medico.index');

    // Gestión de Pacientes por el Médico
    Route::controller(MedicoController::class)->group(function () {
        Route::get('/pacientes', function () {
            return redirect()->route('medico.index', ['vista' => 'pacientes']);
        })->name('medico.pacientes.index');
        Route::get('/pacientes/crear', function () {
            return redirect()->route('medico.index', ['vista' => 'registrar']);
        })->name('medico.pacientes.create');
        Route::post('/pacientes/crear', 'store')->name('medico.pacientes.store');
        Route::get('/pacientes/{id}', 'show')->name('medico.pacientes.show');
        Route::delete('/pacientes/{id}', 'destroy')->name('medico.pacientes.destroy');

        Route::get('/obtener-pacientes', 'listar')->name('medico.pacientes.listar');
        
        Route::get('/representantes/buscar', 'getRepresentantes')->name('medico.representantes.buscar');
    });

    Route::controller(ConsultaController::class)->group(function () {
        Route::get('/obtener-consultasP', 'listarConP')->name('medico.pacientes.consultasP');
        Route::get('/obtener-consultasR', 'listarConR')->name('medico.pacientes.consultasR');
        Route::get('/obtener-enfermedades', 'obtenerEnf');
        Route::get('/precargar-con/{id}', 'precargarConP');
        Route::post('/consulta-registrar1', 'registrarCon1');
        Route::post('/consulta-registrar2', 'registrarCon2');
        Route::get('/obtener-protocolos', 'precargarProts');
        Route::get('/obtener-medicinasProt/{id}', 'precargarMeds');
        Route::get('/obtener-detalles-consulta/{id}', 'ver');
    });

    Route::controller(TratamientoController::class)->group(function () {
        Route::get('/tratamientos/programar', 'create')->name('medico.tratamientos.create');
        Route::post('/tratamientos', 'store')->name('medico.tratamientos.store');
        Route::post('/tratamientos/sesiones', 'storeSesion')->name('medico.tratamientos.sesiones.store');
    });
});

// ---------------------------------------------------------------------
// ENFERMERÍA
// ---------------------------------------------------------------------
    Route::middleware('role:4')->prefix('enf')->controller(EnfermeriaController::class)->group(function () {
        Route::get('/', function () {
            return view('enfermeria.index');
        })->name('enf.index');

        Route::get('/obtener-jornada', 'listarJor');
        Route::get('/obtener-observacion', 'listarObs');
        Route::get('/precargar-trat/{id}', 'precargarTrat');
        Route::post('/observacion-guardar', 'registrarObs')->name('observacion.registrar');

    });

});