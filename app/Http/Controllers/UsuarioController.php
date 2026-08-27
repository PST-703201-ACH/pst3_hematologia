<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Models\Persona;
use App\Models\User;
use App\Models\Rol;
use App\Mail\ClaveOlvidada;
use App\Models\Auditoria;


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

}