<?php

namespace App\Http\Controllers;

use App\Http\Requests\PersonaRequest;
use App\Mail\UsuarioNuevo;
use App\Models\Persona;
use App\Models\User;
use App\Models\Auditoria;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Throwable;

class PersonaController extends Controller
{
    private function formatName(?string $firstPart, ?string $secondPart = null): string
    {
        return trim($firstPart . ($secondPart ? " {$secondPart}" : ''));
    }

    private function normalizeTelefono(?string $telefono): string
    {
        return '0' . preg_replace('/^0+/', '', (string) $telefono);
    }

    public function registrar(PersonaRequest $request)
    {
        $mailFailure = false;

        try {
            if ($request->tipo_reg !== 'usuario') {
                return response()->json([
                    'status' => 'error',
                    'mensaje' => 'Tipo de registro no soportado',
                ], 422);
            }

            return DB::transaction(function () use ($request, &$mailFailure) {
                $persona = Persona::create([
                    'nombres' => $this->formatName($request->nombre1, $request->nombre2),
                    'apellidos' => $this->formatName($request->apellido1, $request->apellido2),
                    'fecha_nacimiento' => $request->fecha_nac,
                    'sexo' => $request->sexo,
                    'cedula' => $request->cedula,
                    'telefono' => $this->normalizeTelefono($request->telefono),
                    'email' => $request->correo,
                    'estado_id' => $request->estado,
                    'municipio_id' => $request->municipio,
                    'parroquia_id' => $request->parroquia,
                    'direccion_exacta' => $request->direccion,
                ]);

                $clave = Str::random(6);
                $cedulaUsuario = preg_replace('/\D+/', '', (string) $request->cedula);

                $usuario = User::create([
                    'username' => $cedulaUsuario,
                    'password_hash' => Hash::make($clave),
                    'persona_id' => $persona->persona_id,
                    'id_rol' => $request->rol,
                    'status' => 2,
                ]);

                try {
                    Mail::to($persona->email)->send(new UsuarioNuevo([
                        'usuario' => "{$persona->nombres} {$persona->apellidos}",
                        'cedula' => $usuario->username,
                        'clave' => $clave,
                    ]));
                } catch (Throwable $e) {
                    $mailFailure = true;
                    throw $e;
                }

                return response()->json([
                    'status' => 'exito',
                    'mensaje' => 'Usuario registrado con exito',
                ]);
            });
        } catch (Throwable $e) {
            Log::error('Fallo en PersonaController::registrar: ' . $e->getMessage());

            return response()->json([
                'status' => 'error',
                'mensaje' => $mailFailure
                    ? 'No se pudo enviar el correo de acceso. Revise la configuración SMTP; no se creó el usuario.'
                    : 'Ocurrio un error interno en el servidor. Intente mas tarde.',
            ], $mailFailure ? 503 : 500);
        }
    }

    public function actualizar(PersonaRequest $request)
    {
        try {
            if ($request->tipo_up !== 'usuario') {
                return response()->json([
                    'status' => 'error',
                    'mensaje' => 'Tipo de actualizacion no soportado',
                ], 422);
            }

            return DB::transaction(function () use ($request) {
                $persona = Persona::findOrFail($request->persona);

                $persona->update([
                    'nombres' => $this->formatName($request->nombre1, $request->nombre2),
                    'apellidos' => $this->formatName($request->apellido1, $request->apellido2),
                    'fecha_nacimiento' => $request->fecha_nac,
                    'sexo' => $request->sexo,
                    'telefono' => $this->normalizeTelefono($request->telefono),
                    'email' => $request->correo,
                    'estado_id' => $request->estado,
                    'municipio_id' => $request->municipio,
                    'parroquia_id' => $request->parroquia,
                    'direccion_exacta' => $request->direccion,
                ]);

                $user = User::where('persona_id', $persona->persona_id)->firstOrFail();
                $user->update(['id_rol' => $request->rol]);

                $usuario = $request->userUp;

                // Guardar para auditar
                $aud = new Auditoria;
                $aud->descripcion = 'Actualizo el perfil de '.$user->persona->nombres.' '.$user->persona->apellidos;
                $aud->modulo = 'Usuarios';
                $aud->id_usuario = $usuario;
                $aud->accion = "Actualizacion";

                if ($aud->save()) {
                    return response()->json([
                        'status' => 'exito',
                        'mensaje' => 'Usuario actualizado con exito',
                    ]);
                }

            });
        } catch (Exception $e) {
            Log::error('Fallo en PersonaController::actualizar: ' . $e->getMessage());

            return response()->json([
                'status' => 'error',
                'mensaje' => 'Ocurrio un error interno en el servidor. Intente mas tarde.',
            ], 500);
        }
    }
}
