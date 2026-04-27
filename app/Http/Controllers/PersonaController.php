<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Persona;
use App\Models\Usuario;
use App\Mail\UsuarioNuevo;

class PersonaController extends Controller
{
    public function store(Request $request)
    {

        $errores = [];

        if (empty($request->nombre1)) {
            $errores['0'] = "Debe escribir primer nombre del nuevo usuario";
        }
        if (empty($request->nombre2)) {
            $errores['0.5'] = "Debe escribir segundo nombre del nuevo usuario";
        }
        if (empty($request->apellido1)) {
            $errores['1'] = "Debe escribir el primer apellido del nuevo usuario";
        }
        if (empty($request->apellido2)) {
            $errores['1.5'] = "Debe escribir el segundo apellido del nuevo usuario";
        }
        if (empty($request->fecha_nac)) {
            $errores['2'] = "";
        }
        if (empty($request->sexo)) {
            $errores['3'] = "Seleccione el genero del nuevo usuario";
        }
        if (empty($request->cedula)) {
            $errores['4'] = "Debe escribir el numero de cedula de identidad del nuevo usuario";
        }
        if (empty($request->apellido2)) {
            $errores['5'] = "Debe escribir el numero telefonico del nuevo usuario";
        }
        if (empty($request->apellido2)) {
            $errores['6'] = "Debe escribir la direccion de correo electronico del nuevo usuario";
        }
        if (empty($request->estado)) {
            $errores['7'] = "Seleccione el estado donde vive el nuevo usuario";
        }
        if (empty($request->municipio)) {
            $errores['8'] = "Seleccione el municipio donde vive el nuevo usuario";
        }
        if (empty($request->parroquia)) {
            $errores['9'] = "Seleccione la parroquia donde vive el nuevo usuario";
        }
        if (empty($request->direccion)) {
            $errores['10'] = "Debe escribir la direccion de domicilio exacta del usuario";
        }
        if (empty($request->rol)) {
            $errores['11'] = "Seleccione el rol del nuevo usuario";
        }
        if (!empty($errores)) {
            return response()->json([
                "status" => "errores",
                "errores" => $errores
            ]);
            exit;
        }

        try{
            $persona = new Persona();

            $persona->nombres = $request->nombre1." ".$request->nombre2;
            $persona->apellidos = $request->apellido1." ".$request->apellido2;
            $persona->fecha_nacimiento = $request->fecha_nac;
            $persona->sexo = $request->sexo;

            $persona->cedula = $request->cedula;
            $persona->telefono = $request->telefono;
            $persona->email = $request->correo;
            $persona->estado_id = $request->estado;
            $persona->municipio_id = $request->municipio;
            $persona->parroquia_id = $request->parroquia;
            $persona->direccion_exacta = $request->direccion;
            
            if ($persona->save()) { 
                
                switch ($request->tipo_reg) {
                    case 'usuario':
                        $usuario = new Usuario();
                        $clave = generarPassword(12);
                        $usuario->password_hash = password_hash($clave, PASSWORD_DEFAULT);
                        $usuario->persona_id = $persona->persona_id;
                        $usuario->username = $request->cedula;
                        $usuario->id_rol = $request->rol;

                        function generarPassword($longitud = 12) {
                            $caracteres = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
                            $password = '';
                            $max = strlen($caracteres) - 1;
                            for ($i = 0; $i < $longitud; $i++) {
                                $password .= $caracteres[random_int(0, $max)];
                            }
                            return $password;
                        }

                        if($usuario->save()){

                            $infoUsu = [
                                'usuario' => $persona->nombres.' '.$persona->apellidos,
                                'cedula' => $usuario->username,
                                'clave' => $clave
                            ];

                            Mail::to($persona->email)->send(new UsuarioNuevo($infoUsu));

                            return response()->json(['status' => 'exito', 'mensaje' => 'Usuario registrado con exito', 'Usuario' => $usuario->username]);    
                        }               break;
                }
            }
            return response()->json(['status' => 'error', 'mensaje' => 'No se pudo registrar la persona']);
        }catch (\Exception $e) {
            
            return response()->json([
                'status' => 'error', 
                'mensaje' => 'Error de servidor o base de datos: '
            ]);
        }
    }

}
