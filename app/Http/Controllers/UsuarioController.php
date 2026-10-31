<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Persona;
use App\Models\User;
use App\Models\Rol;
use App\Mail\ClaveOlvidada;
use App\Models\Auditoria;
use Symfony\Component\Process\Process;


class UsuarioController extends Controller
{

    public function listar(Request $request){
        $query = User::with('persona', 'rol');

        if ($request->filled('busqueda')) {
            $termino = $request->input('busqueda');
            
            $query->where(function($q) use ($termino) {
                $q->where('username', 'ILIKE', "%{$termino}%")
                  ->orWhereHas('persona', function($subQuery) use ($termino) {
                      $subQuery->where('nombres', 'ILIKE', "%{$termino}%")
                               ->orWhere('apellidos', 'ILIKE', "%{$termino}%")
                               ->orWhereRaw("CONCAT(nombres, ' ', apellidos) ILIKE ?", ["%{$termino}%"])
                               ->orWhere('telefono', 'ILIKE', "%{$termino}%")
                               ->orWhere('cedula', 'ILIKE', "%{$termino}%");
                  });
            });     
        }

        if ($request->filled('status')) {
            $status = $request->input('status');
            
            $query->where('status', (int)$status); 
        }

        if ($request->filled('rol')) {
            $rol = $request->input('rol');
            
            $query->where('id_rol', (int)$rol); 
        }

        // Guardar para auditar
        $aud = new Auditoria;
        $aud->descripcion = 'Ingreso al listado de usuarios en el sistema';
        $aud->modulo = 'Usuarios';
        $aud->id_usuario = 1;
        $aud->accion = "Listado";

        if ($aud->save()) {
            return response()->json($query->get());
        }
    }    

    public function ver($id){
        $usuario = User::with('persona.estado', 'persona.municipio', 'persona.parroquia', 'rol')->where('persona_id', $id)->first();
        return response()->json($usuario);
    }

    public function precargar($id){
        $usuario = User::with('persona.estado', 'persona.municipio', 'persona.parroquia', 'rol')->where('persona_id', $id)->first();
        return response()->json($usuario);
    }

    public function nuevo_status($id){
        $usuario = User::where('persona_id', $id)->first();

        if ($usuario) {
            try{
                switch ($usuario->status) {
                    case '1':
                        $usuario->update([
                            'status' => '0'
                        ]);
                        return response()->json(['status' => 'exito', 'contenido' => '<p class="text-danger">Inactivo</p>']);
                    break;

                    case '0':
                        $usuario->update([
                            'status' => '1'
                        ]);
                        return response()->json(['status' => 'exito', 'contenido' => '<p class="text-success">Activo</p>']);
                    break;
                }
            } catch (\Exception $e) {
                return response()->json([
                    'status' => 'error'
                ]);
            }
        }
    }

    public function generarPassword($longitud = 12) {
        $caracteres = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $password = '';
        $max = strlen($caracteres) - 1;
            for ($i = 0; $i < $longitud; $i++) {
                $password .= $caracteres[random_int(0, $max)];
            }
        return $password;
    }

    public function olvide_clave($username){

        if (empty($username)) {
            return response()->json([
                'status' => 'alerta', 
                'aviso' => 'Debe ingresar su numero de cedula de identidad'
            ]);
        } else if (!is_numeric($username)){
            return response()->json([
                'status' => 'alerta', 
                'aviso' => 'Solo puede contener numeros'
            ]);
        } else if (strlen($username) < 7 || strlen($username) > 8){
            return response()->json([
                'status' => 'alerta', 
                'aviso' => 'Debe contener de 7 a 8 digitos'
            ]);
        }

        $usuario = User::where('username', $username)->first();

        if ($usuario) {
            try {
                $email = $usuario->persona?->email;

                if (!$email) {
                    return response()->json([
                        'status' => 'error', 
                        'aviso' => 'El usuario no tiene un correo electrónico registrado.'
                    ]);
                }

                $clave = $this->generarPassword(12);

                $usuario->update([
                    'status' => 2,
                    'password_hash' => bcrypt($clave) 
                ]);
                
                $infoUsu = [
                    'cedula' => $usuario->username,
                    'clave' => $clave,
                ];

                Mail::to($email)->send(new ClaveOlvidada($infoUsu));

                list($partUsuario, $partDominio) = explode('@', $email);

                $primerCaracter = substr($partUsuario, 0, 2);
                $ultimoCaracter = substr($partUsuario, -1);

                $censurado = str_repeat('*', max(3, strlen($partUsuario) - 2));
                $emailCensurado = $primerCaracter . $censurado . $ultimoCaracter . '@' . $partDominio;


                return response()->json([
                    'status' => 'exito', 
                    'aviso' => "Se ha cambiado tu contraseña y se ha enviado a $emailCensurado"
                ]);

            } catch (\Exception $e) {
                return response()->json([
                    'status' => 'error',
                    'aviso' => 'Error al enviar el correo. Inténtalo más tarde.'
                ]);
            }
        }

        return response()->json([
            'status' => 'alerta',
            'aviso' => 'La cédula de identidad ingresada no está registrada.'
        ]);
    }


    public function nueva_clave(Request $request) {
        
        if (empty($request->camClave)) {
            $errores['camClave'] = "Debe ingresar la nueva contraseña";
        }

        if (empty($request->camVerificar)) {
            $errores['camVerificar'] = "Ingrese la nueva contraseña otra vez";
        }

        if ($request->camVerificar !== $request->camClave) {
            $errores['camVerificar'] = "Las contraseñas no coinciden";
        }

        $username = $request->camCedula;
        $clave = $request->camClave;
        
        if (strlen($clave) < 8 ){
            $errores['camClave'] = "La contraseña debe tener al menos 8 caracteres";
        }

        if (!empty($errores)) {
            return response()->json([
                "status" => "errores",
                "errores" => $errores
            ], 422);
        }

        $usuario = User::where('username', $username)->first();

        if ($usuario) {
            $usuario->update([
                'status' => 1,
                'password_hash' => bcrypt($clave) 
            ]);
            return response()->json([
                'status' => 'exito', 
                'mensaje' => 'Se ha actualizado la contraseña correctamente'
            ]);
        } else {
            return response()->json([
                'status' => 'error', 
                'mensaje' => 'Usuario no encontrado, consulta con el administrador'
            ]);
        }

        return response()->json([
            'status' => 'error', 
            'mensaje' => 'Error, consulta con el administrador'
        ]);
    }

    public function auditar(Request $request){
        $query = Auditoria::with([
            // Asegúrate de incluir la Clave Primaria real de Usuario (ej. id)
            'usuario:usuario_id,persona_id', 
            'usuario.persona:persona_id,nombres,apellidos'
        ]);

        if ($request->filled('busqueda')) {
            $termino = $request->input('busqueda');
            
            $query->where(function($q) use ($termino) {
                $q->where('descripcion', 'ILIKE', "%{$termino}%")
                  ->orWhere('modulo', 'ILIKE', "%{$termino}%")
                  ->orWhereRaw('fecha_hora::text ILIKE ?', ["%{$termino}%"]);
                  
                // Corrección de orWhereHas anidado correctamente
                $q->orWhereHas('usuario', function ($uQ) use ($termino) {
                    $uQ->whereHas('persona', function ($pQ) use ($termino) {
                        $pQ->where('nombres', 'ILIKE', "%{$termino}%")
                           ->orWhere('apellidos', 'ILIKE', "%{$termino}%")
                           ->orWhereRaw("CONCAT(nombres, ' ', apellidos) ILIKE ?", ["%{$termino}%"]);
                    });
                });
            });    
        }


        if ($request->filled('mod')) {
            $mod = $request->input('mod');
            $query->where('modulo', $mod);
        }

        if ($request->filled('acc')) {
            $acc = $request->input('acc');
            $query->where('accion', $acc);
        }

        if ($request->filled('fecha')) {
            $fecha = $request->input('fecha');
            $query->whereDate('fecha_hora', $request->input('fecha'));
        }

        return response()->json($query->get());
    }

    public function auditarPdf()
    {

        $auditorias = Auditoria::with('usuario.persona')->get();

        $data = [
            'auditorias' => $auditorias,
            'fecha_emision' => now()->format('d/m/Y H:i:s')
        ];

        $pdf = Pdf::loadView('reportes.auditoria_pdf', $data);

        return $pdf->download("reporte_auditoria_" . date('Ymd_His') . ".pdf");
    }

    /**
     * Genera un respaldo SQL de PostgreSQL, registra la descarga y entrega el archivo al administrador.
     */
    public function respaldarBaseDatos()
    {
        // El respaldo usa la conexión PostgreSQL nombrada, aunque otra conexión sea la predeterminada.
        $configuracion = config('database.connections.pgsql');
        $pgDump = config('database.pg_dump_binary');

        if (
            !is_array($configuracion)
            || empty($configuracion['host'])
            || empty($configuracion['port'])
            || empty($configuracion['database'])
            || empty($configuracion['username'])
        ) {
            throw new \RuntimeException('La configuración de conexión PostgreSQL está incompleta.');
        }

        // Valida que el ejecutable configurado sea una ruta absoluta existente.
        $rutaAbsoluta = PHP_OS_FAMILY === 'Windows'
            ? preg_match('/^[A-Za-z]:[\\\\\/]/', (string) $pgDump) === 1
            : str_starts_with((string) $pgDump, '/');

        if (!$rutaAbsoluta || !is_file($pgDump)) {
            throw new \RuntimeException('Configura PG_DUMP_BINARY con la ruta absoluta existente de pg_dump.exe.');
        }

        // El respaldo se crea en almacenamiento privado con un nombre apto para descarga.
        $directorio = storage_path('app/backups');
        File::ensureDirectoryExists($directorio, 0700, true);
        $rutaAbsolutaSalida = storage_path('app/backups/backup_' . date('Y_m_d_His') . '.sql');

        $respaldoListo = false;

        try {
            // Una llave criptográficamente aleatoria permite que pg_dump no dependa de la generación aleatoria de Windows.
            $restrictKey = bin2hex(random_bytes(32));

            // Symfony escapa cada placeholder dinámico para el shell de Windows y mantiene la sintaxis --llave=valor.
            $comandoString = '"${:PG_DUMP}" "${:HOST_ARG}" "${:PORT_ARG}" "${:USER_ARG}" "${:DATABASE_ARG}" "${:FORMAT_ARG}" "${:RESTRICT_ARG}" "${:FILE_ARG}"';
            $variablesProceso = [
                'PG_DUMP' => $pgDump,
                'HOST_ARG' => '--host=' . $configuracion['host'],
                'PORT_ARG' => '--port=' . $configuracion['port'],
                'USER_ARG' => '--username=' . $configuracion['username'],
                'DATABASE_ARG' => '--dbname=' . $configuracion['database'],
                'FORMAT_ARG' => '--format=plain',
                'RESTRICT_ARG' => '--restrict-key=' . $restrictKey,
                'FILE_ARG' => '--file=' . $rutaAbsolutaSalida,
                'PGPASSWORD' => (string) ($configuracion['password'] ?? ''),
                'PGSSLMODE' => (string) ($configuracion['sslmode'] ?? 'prefer'),
            ];

            // Conserva las rutas del sistema en el entorno hijo para que Windows cargue sus proveedores criptográficos.
            if (PHP_OS_FAMILY === 'Windows') {
                $directorioWindows = getenv('SYSTEMROOT') ?: getenv('WINDIR');

                if (!$directorioWindows) {
                    throw new \RuntimeException('Windows no expuso SYSTEMROOT ni WINDIR al proceso de Laravel.');
                }

                $variablesProceso['SYSTEMROOT'] = $directorioWindows;
                $variablesProceso['WINDIR'] = $directorioWindows;
            }

            // Se ejecuta la misma línea de comandos que en CMD y se conserva la contraseña fuera de ella.
            $proceso = Process::fromShellCommandline($comandoString, base_path(), $variablesProceso);
            $proceso->setTimeout(3600);
            $proceso->run();

            if (!$proceso->isSuccessful()) {
                // pg_dump suele emitir texto CP1252 en Windows; convertirlo evita errores UTF-8 en el log.
                $detalleError = mb_convert_encoding($proceso->getErrorOutput(), 'UTF-8', 'CP1252');
                $detalleError = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/', '', $detalleError) ?? '';

                // Conserva el diagnóstico en el log sin insertar texto inválido en la página de error.
                Log::error('Falló la generación del respaldo PostgreSQL con pg_dump.', [
                    'exit_code' => $proceso->getExitCode(),
                    'stdout' => mb_substr(
                        mb_convert_encoding($proceso->getOutput(), 'UTF-8', 'CP1252'),
                        0,
                        1000,
                        'UTF-8'
                    ),
                    'stderr' => mb_substr($detalleError, 0, 4000, 'UTF-8'),
                    'command' => $proceso->getCommandLine(),
                ]);

                throw new \RuntimeException(
                    'No se pudo generar el respaldo PostgreSQL. Revisa la configuración de pg_dump y los registros de Laravel.'
                );
            }

            if (!is_file($rutaAbsolutaSalida) || filesize($rutaAbsolutaSalida) === 0) {
                throw new \RuntimeException('pg_dump no generó un archivo de respaldo válido.');
            }

            $administrador = Auth::user();
            abort_unless($administrador instanceof User, 403);

            // La descarga queda auditada antes de enviar el archivo al navegador.
            $auditoria = new Auditoria();
            $auditoria->descripcion = 'Descarga de respaldo de la base de datos PostgreSQL';
            $auditoria->modulo = 'Seguridad / Backup';
            // La tabla usuarios.auditoria usa id_usuario para guardar el usuario_id autenticado.
            $auditoria->id_usuario = $administrador->usuario_id;
            $auditoria->accion = 'Respaldo';
            $auditoria->fecha_hora = now();
            $auditoria->saveOrFail();

            // Evita almacenar el respaldo tras la descarga y deshabilita su almacenamiento en caché.
            $respuesta = response()->download($rutaAbsolutaSalida, basename($rutaAbsolutaSalida), [
                'Content-Type' => 'application/sql; charset=UTF-8',
                'Cache-Control' => 'no-store, private',
            ])->deleteFileAfterSend(true);

            $respaldoListo = true;

            return $respuesta;
        } finally {
            // Si el proceso falla antes de iniciar la descarga, elimina el archivo parcial.
            if (!$respaldoListo && is_file($rutaAbsolutaSalida)) {
                File::delete($rutaAbsolutaSalida);
            }
        }
    }


}