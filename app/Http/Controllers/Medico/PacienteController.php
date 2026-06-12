<?php

namespace App\Http\Controllers\Medico;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Persona;
use App\Models\Estado;
use App\Models\Paciente;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use DateTime;

class PacienteController extends Controller
{

    public function listar(Request $request)
    {
        $query = Paciente::with('persona');
        return response()->json($query->get());
    }

    public function create()
    {
        $estados = Estado::all();
        return view('medico.pacientes.create', compact('estados'));
    }

    public function store(Request $request)
    {

        $errores = [];



        if (empty($request->reg_nombre1)) {
            $errores['reg_nombre1'] = "Debe escribir primer nombre del nuevo usuario";
        } else if (!preg_match("/^[\p{L}\s]+$/u", $request->reg_nombre1)) {
            $errores['reg_nombre1'] = "El primer nombre solo puede tener letras";
        }

        if (!empty($request->reg_nombre2) && !preg_match("/^[\p{L}\s]+$/u", $request->reg_nombre2)) {
            $errores['reg_nombre2'] = "El segundo nombre solo puede tener letras";
        }

        if (empty($request->reg_apellido1)) {
            $errores['reg_apellido1'] = "Debe escribir el primer apellido del nuevo usuario";
        } else if (!preg_match("/^[\p{L}\s]+$/u", $request->reg_apellido1)) {
            $errores['reg_apellido1'] = "El primer apellido solo puede tener letras";
        }

        if (!empty($request->reg_apellido2) && !preg_match("/^[\p{L}\s]+$/u", $request->reg_apellido2)) {
            $errores['reg_apellido2'] = "El segundo apellido solo puede tener letras";
        }

        $nacimiento = new DateTime($request->reg_fecha_nac);
        $hoy = new DateTime();

        $edad = $hoy->diff($nacimiento);
        if (empty($request->reg_fecha_nac)) {
            $errores['reg_fecha_nac'] = "Debe ingresar la fecha de nacimiento del nuevo paciente";
        } else if ($edad->y > 18) {
            $errores['reg_fecha_nac'] = "El nuevo paciente ya es mayor de edad";
        }

        if (empty($request->reg_sexo)) {
            $errores['reg_sexo'] = "Seleccione el genero del nuevo paciente";
        }

        if (empty($request->reg_cedula)) {
            $errores['reg_cedula'] = "Debe escribir el numero de cedula de identidad del nuevo paciente";
        } else if (!is_numeric($request->reg_cedula)) {
            $errores['reg_cedula'] = "Solo se permiten numeros";
        } else if (strlen($request->reg_cedula) < 7 || strlen($request->reg_cedula) > 8) {
            $errores['reg_cedula'] = "El numero de cedula debe tener de 7 a 8 digitos";
        }

        $cedula = $request->reg_nac.'-'.$request->reg_cedula; 

        $existente = Paciente::whereHas('persona', function ($query) use ($cedula) {
            $query->where('cedula', $cedula);
        })->first();

        if ($existente) {
            $errores['reg_cedula'] = "Este paciente ya esta registrado";
        }

        if (empty($request->reg_telefono)) {
            $errores['reg_telefono'] = "Debe escribir el numero telefonico del nuevo paciente";
        } else if (!is_numeric($request->reg_telefono)) {
            $errores['reg_telefono'] = "Solo se permiten numeros";
        } else if (strlen($request->reg_telefono) < 10 || strlen($request->reg_telefono) > 10) {
            $errores['reg_telefono'] = "El numero telefonico debe tener 11 digitos";
        } else {
            $telefono = '0'.$request->reg_telefono;
            $codigo = substr($telefono, 0, 4);
            if ($codigo !== '0424' && $codigo !== '0414' && $codigo !== '0412' && $codigo !== '0416' && $codigo !== '0426'){
                    $errores['reg_telefono'] = "Codigo de numero telefonico invalido";
            }
        }

        if (empty($request->reg_email)) {
            $errores['reg_email'] = "Debe escribir la direccion de correo electronico del nuevo paciente";
        } else if (!filter_var($request->reg_email, FILTER_VALIDATE_EMAIL)) {
            $errores['reg_email'] = "La direccion de correo electronico tiene un formato invalido";
        }

        if (empty($request->reg_estado)) {
            $errores['reg_estado'] = "Seleccione el estado donde vive el nuevo paciente";
        }

        if (empty($request->reg_municipio)) {
            $errores['reg_municipio'] = "Seleccione el municipio donde vive el nuevo paciente";
        }

        if (empty($request->reg_parroquia)) {
            $errores['reg_parroquia'] = "Seleccione la parroquia donde vive el nuevo paciente";
        }

        if (empty($request->reg_direccion)) {
            $errores['reg_direccion'] = "Debe escribir la direccion de domicilio exacta del paciente";
        } else if (!preg_match('/^[a-zA-Z0-9 ]+$/', $request->reg_direccion)) {
            $errores['reg_direccion'] = "La direccion de domicilio no puede tener caracteres especiales";
        }

        if (!empty($errores)) {
            return response()->json([
                "status" => "errores",
                "errores" => $errores
            ]);
            exit;
        }

        try {
            DB::beginTransaction();

            $nextPersonaId = (Persona::max('persona_id') ?? 0) + 1;
            
            $persona = Persona::create([
                'persona_id' => $nextPersonaId,
                'cedula' => $request->reg_cedula,
                'nombres' => $request->reg_nombre1." ".$request->reg_nombre2,
                'apellidos' => $request->reg_apellido1." ".$request->reg_apellido2,
                'fecha_nacimiento' => $reques->reg_fecha_nac,
                'sexo' => $request->reg_sexo,
                'telefono' => $request->reg_telefono,
                'email' => $request->reg_email,
                'estado_id' => $request->reg_estado,
                'municipio_id' => $request->reg_municipio,
                'parroquia_id' => $request->reg_parroquia,
                'direccion_exacta' => $request->reg_direccion,
            ]);

            if (!$persona) {
                return response()->json(['status' => 'error', 'mensaje' => 'No se pudo registrar los datos personales del paciente correctamente']);
            }

            $nextPacienteId = (Paciente::max('paciente_id') ?? 0) + 1;
            
            $paciente = Paciente::create([
                'paciente_id' => $nextPacienteId,
                'persona_id' => $persona->persona_id,
                'hc' => $request->reg_hc,
                'status' => 'Activo',
            ]);

            if (!$paciente) {
                return response()->json(['status' => 'error', 'mensaje' => 'No se pudo registrar el paciente correctamente']);
            }

            DB::commit();


            return response()->json(['status' => 'exito', 'mensaje' => "Paciente registrado exitosamente. Historia Clínica N°: {$request->reg_hc}"]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error crítico al registrar paciente: ' . $e->getMessage(), [
                'request' => $request->all(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return back()->withInput()
                ->withErrors(['error' => 'No se pudo completar el registro: ' . $e->getMessage()]);
        }
    }

    /**
     * Elimina un paciente y su información personal (Temporal).
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $paciente = Paciente::findOrFail($id);
            $personaId = $paciente->persona_id;

            // Eliminamos primero al paciente por la FK
            $paciente->delete();
            
            // Eliminamos la persona asociada
            Persona::where('persona_id', $personaId)->delete();

            DB::commit();

            return redirect()->route('medico.pacientes.index')
                ->with('success', 'Registro eliminado correctamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al eliminar paciente: ' . $e->getMessage());
            return back()->withErrors(['error' => 'No se pudo eliminar el registro: ' . $e->getMessage()]);
        }
    }
}
