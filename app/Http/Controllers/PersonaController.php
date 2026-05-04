<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Persona;
use App\Models\Usuario;
use App\Mail\UsuarioActualizado;
use App\Mail\UsuarioNuevo;
use Illuminate\Support\Facades\Mail;
use DateTime;

class PersonaController extends Controller
{
    public function generarPassword($longitud = 12) {
        $caracteres = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $password = '';
        $max = strlen($caracteres) - 1;
            for ($i = 0; $i < $longitud; $i++) {
                $password .= $caracteres[random_int(0, $max)];
            }
        return $password;
    }

    public function registrar(Request $request)
    {

        $errores = [];

        if (empty($request->nombre1)) {
            $errores['0'] = "Debe escribir primer nombre del nuevo usuario";
        } else if (!preg_match("/^[\p{L}\s]+$/u", $request->nombre1)) {
            $errores['0'] = "El primer nombre solo puede tener letras";
        }

        if (empty($request->nombre2)) {
            $errores['0.5'] = "Debe escribir segundo nombre del nuevo usuario";
        } else if (!preg_match("/^[\p{L}\s]+$/u", $request->nombre2)) {
            $errores['0.5'] = "El segundo nombre solo puede tener letras";
        }

        if (empty($request->apellido1)) {
            $errores['1'] = "Debe escribir el primer apellido del nuevo usuario";
        } else if (!preg_match("/^[\p{L}\s]+$/u", $request->apellido1)) {
            $errores['1'] = "El primer apellido solo puede tener letras";
        }

        if (empty($request->apellido2)) {
            $errores['1.5'] = "Debe escribir el segundo apellido del nuevo usuario";
        } else if (!preg_match("/^[\p{L}\s]+$/u", $request->apellido2)) {
            $errores['1.5'] = "El segundo apellido no puede contener caracteres especiales";
        }
        switch ($request->tipo_reg){
            case 'usuario':
                $nacimiento = new DateTime($request->fecha_nac);
                $hoy = new DateTime();

                $edad = $hoy->diff($nacimiento);
                if (empty($request->fecha_nac)) {
                    $errores['2'] = "Debe ingresar la fecha de nacimiento del nuevo usuario";
                } else if ($edad->y < 18) {
                    $errores['2'] = "El nuevo usuario es menor de edad";
                }                
                break;
        }
        
        if (empty($request->sexo)) {
            $errores['3'] = "Seleccione el genero del nuevo usuario";
        }

        if (empty($request->cedula)) {
            $errores['4.5'] = "Debe escribir el numero de cedula de identidad del nuevo usuario";
        } else if (!is_numeric($request->cedula)) {
            $errores['4.5'] = "Solo se permiten numeros";
        } else if (strlen($request->cedula) < 7 || strlen($request->cedula) > 8) {
            $errores['4.5'] = "El numero de cedula debe tener de 7 a 8 digitos";
        }

        $cedula = $request->nacionalidad.'-'.$request->cedula; 

        if (empty($request->telefono)) {
            $errores['5'] = "Debe escribir el numero telefonico del nuevo usuario";
        } else if (!is_numeric($request->telefono)) {
            $errores['5'] = "Solo se permiten numeros";
        } else if (strlen($request->telefono) < 10 || strlen($request->telefono) > 10) {
            $errores['5'] = "El numero telefonico debe tener 11 digitos";
        } else {
            $telefono = '0'.$request->telefono;
            $codigo = substr($telefono, 0, 4);
            if ($codigo !== '0424' && $codigo !== '0414' && $codigo !== '0412' && $codigo !== '0416' && $codigo !== '0426'){
                    $errores['5'] = "Codigo de numero telefonico invalido";
            }
        }

        if (empty($request->correo)) {
            $errores['6'] = "Debe escribir la direccion de correo electronico del nuevo usuario";
        } else if (!filter_var($request->correo, FILTER_VALIDATE_EMAIL)) {
            $errores['6'] = "La direccion de correo electronico tiene un formato invalido";
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
        } else if (!preg_match('/^[a-zA-Z0-9 ]+$/', $request->direccion)) {
            $errores['10'] = "La direccion de domicilio no puede tener caracteres especiales";
        }

        if (empty($request->rol)) {
            $errores['11'] = "";
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

            $persona->cedula = $cedula;
            $persona->telefono = $telefono;
            $persona->email = $request->correo;
            $persona->estado_id = $request->estado;
            $persona->municipio_id = $request->municipio;
            $persona->parroquia_id = $request->parroquia;
            $persona->direccion_exacta = $request->direccion;
            
            if ($persona->save()) { 
                
                switch ($request->tipo_reg) {
                    
                    case 'usuario':
                        $usuario = new Usuario();
                        $clave = $this->generarPassword(12);
                        $usuario->password_hash = password_hash($clave, PASSWORD_DEFAULT);
                        $usuario->persona_id = $persona->persona_id;
                        $usuario->username = $request->cedula;
                        $usuario->id_rol = $request->rol;
                        $usuario->status = 1;

                        if($usuario->save()){

                            $infoUsu = [
                                'usuario' => $persona->nombres.' '.$persona->apellidos,
                                'cedula' => $usuario->username,
                                'clave' => $clave
                            ];

                            Mail::to($persona->email)->send(new UsuarioNuevo($infoUsu));

                            return response()->json(['status' => 'exito', 'mensaje' => 'Usuario registrado con exito']);    
                        }               
                    break;
                }
            }
            return response()->json(['status' => 'error', 'mensaje' => 'No se pudo registrar la persona']);
        }catch (\Exception $e) {
            
            return response()->json([
                'status' => 'error', 
                'mensaje' => 'Error de servidor o base de datos: '. $e->getMessage()
            ]);
        }

    }

    public function actualizar(Request $request)
    {

        $errores = [];

        if (empty($request->nombre1)) {
            $errores['actualizar0'] = "Debe escribir primer nombre del nuevo usuario";
        } else if (!preg_match("/^[\p{L}\s]+$/u", $request->nombre1)) {
            $errores['actualizar0'] = "El primer nombre solo puede tener letras";
        }

        if (empty($request->nombre2)) {
            $errores['actualizar0.5'] = "Debe escribir segundo nombre del nuevo usuario";
        } else if (!preg_match("/^[\p{L}\s]+$/u", $request->nombre2)) {
            $errores['actualizar0.5'] = "El segundo nombre solo puede tener letras";
        }

        if (empty($request->apellido1)) {
            $errores['actualizar1'] = "Debe escribir el primer apellido del nuevo usuario";
        } else if (!preg_match("/^[\p{L}\s]+$/u", $request->apellido1)) {
            $errores['actualizar1'] = "El primer apellido solo puede tener letras";
        }

        if (empty($request->apellido2)) {
            $errores['actualizar1.5'] = "Debe escribir el segundo apellido del nuevo usuario";
        } else if (!preg_match("/^[\p{L}\s]+$/u", $request->apellido2)) {
            $errores['actualizar1.5'] = "El segundo apellido no puede contener caracteres especiales";
        }
        switch ($request->tipo_reg){
            case 'usuario':
                $nacimiento = new DateTime($request->fecha_nac);
                $hoy = new DateTime();

                $edad = $hoy->diff($nacimiento);
                if (empty($request->fecha_nac)) {
                    $errores['actualizar2'] = "Debe ingresar la fecha de nacimiento del nuevo usuario";
                } else if ($edad->y < 18) {
                    $errores['actualizar2'] = "El nuevo usuario es menor de edad";
                }                
                break;
        }
        
        if (empty($request->sexo)) {
            $errores['actualizar3'] = "Seleccione el genero del nuevo usuario";
        }

        if (empty($request->cedula)) {
            $errores['actualizar4.5'] = "Debe escribir el numero de cedula de identidad del nuevo usuario";
        } else if (!is_numeric($request->cedula)) {
            $errores['actualizar4.5'] = "Solo se permiten numeros";
        } else if (strlen($request->cedula) < 7 || strlen($request->cedula) > 8) {
            $errores['actualizar4.5'] = "El numero de cedula debe tener de 7 a 8 digitos";
        }

        $cedula = $request->nacionalidad.'-'.$request->cedula; 

        if (empty($request->telefono)) {
            $errores['actualizar5'] = "Debe escribir el numero telefonico del nuevo usuario";
        } else if (!is_numeric($request->telefono)) {
            $errores['actualizar5'] = "Solo se permiten numeros";
        } else if (strlen($request->telefono) < 10 || strlen($request->telefono) > 10) {
            $errores['actualizar5'] = "El numero telefonico debe tener 11 digitos";
        } else {
            $telefono = '0'.$request->telefono;
            $codigo = substr($telefono, 0, 4);
            if ($codigo !== '0424' && $codigo !== '0414' && $codigo !== '0412' && $codigo !== '0416' && $codigo !== '0426'){
                    $errores['actualizar5'] = "Codigo de numero telefonico invalido";
            }
        }

        if (empty($request->correo)) {
            $errores['actualizar6'] = "Debe escribir la direccion de correo electronico del nuevo usuario";
        } else if (!filter_var($request->correo, FILTER_VALIDATE_EMAIL)) {
            $errores['actualizar6'] = "La direccion de correo electronico tiene un formato invalido";
        }

        if (empty($request->estado)) {
            $errores['actualizar7'] = "Seleccione el estado donde vive el nuevo usuario";
        }

        if (empty($request->municipio)) {
            $errores['actualizar8'] = "Seleccione el municipio donde vive el nuevo usuario";
        }

        if (empty($request->parroquia)) {
            $errores['actualizar9'] = "Seleccione la parroquia donde vive el nuevo usuario";
        }

        if (empty($request->direccion)) {
            $errores['actualizar10'] = "Debe escribir la direccion de domicilio exacta del usuario";
        } else if (!preg_match('/^[a-zA-Z0-9 ]+$/', $request->direccion)) {
            $errores['actualizar10'] = "La direccion de domicilio no puede tener caracteres especiales";
        }

        if (empty($request->rol)) {
            $errores['actualizar11'] = "";
        }

        if (!empty($errores)) {
            return response()->json([
                "status" => "errores",
                "errores" => $errores
            ]);
            exit;
        }

        try{
            $persona = Persona::find($request->persona);

            if($persona->update([
                'nombres' => $request->nombre1." ".$request->nombre2,
                'apellidos' => $request->apellido1." ".$request->apellido2,
                'fecha_nacimiento' => $request->fecha_nac,
                'sexo' => $request->sexo,
                'cedula' => $cedula,
                'telefono' => $telefono,
                'email' => $request->correo,
                'estado_id' => $request->estado,
                'municipio_id' => $request->municipio,
                'parroquia_id' => $request->parroquia,
                'direccion_exacta' => $request->direccion,
            ])) {
                    switch ($request->tipo_up) {
                        
                        case 'usuario':
                            $usuario = Usuario::where('persona_id', $persona->persona_id)->first();
                            if ($usuario) {
                                $clave = $this->generarPassword(12);
                                if ($usuario->update([
                                
                                'password_hash' => password_hash($clave, PASSWORD_DEFAULT),
                                'persona_id' => $persona->persona_id,
                                'username' => $request->cedula,
                                'id_rol' => $request->rol,
                                'status' => 1,
                                ])){
                                    $infoUsu = [
                                        'usuario' => $persona->nombres.' '.$persona->apellidos,
                                        'cedula' => $usuario->username,
                                        'clave' => $clave,
                                    ];

                                    Mail::to($persona->email)->send(new UsuarioActualizado($infoUsu));

                                    return response()->json(['status' => 'exito', 'mensaje' => 'Usuario actualizado con exito']);
                                }               
                            }
                        break;        
                    }
                }
                return response()->json(['status' => 'error', 'mensaje' => 'No se pudo actualizar el usuario correctamente']);            
        }catch (\Exception $e) {
            
            return response()->json([
                'status' => 'error', 
                'mensaje' => 'Error de servidor o base de datos: '. $e->getMessage()
            ]);
        }
    }
}

