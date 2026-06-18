<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Models\Cita;

class CitaController extends Controller
{
    public function agendar(Request $request)
    {
        $errores = [];

        if (empty($request->pacienteNombre1)) {
            $errores['pacienteNombre1'] = "Debe ingresar el primer nombre del paciente";
        }

        if (empty($request->pacienteApellido1)) {
            $errores['pacienteApellido1'] = "Debe ingresar el primer apellido del paciente";
        }

        if (empty($request->represNombre1)) {
            $errores['represNombre1'] = "Debe ingresar el primer nombre del representante";
        }

        if (empty($request->represApellido1)) {
            $errores['represApellido1'] = "Debe ingresar el primer apellido del representante";
        }

        if (empty($request->hc)) {
            $errores['hc'] = "Debe ingresar el nº de historia clinica del nuevo paciente";
        }

        if (empty($request->fechaHora)) {
            $errores['fechaHora'] = "Debe ingresar la hora para la cita";
        }

        if (!empty($errores)) {
            return response()->json([
                "status" => "errores",
                "errores" => $errores
            ]);
            exit;
        }

        try {
            $cita = new Cita();

            if (!empty($request->pacienteNombre2)) {
                $cita->nombres_paciente = $request->pacienteNombre1." ".$request->pacienteNombre2;
            } else {
                $cita->nombres_paciente = $request->pacienteNombre1;
            }

            if (!empty($request->pacienteApellido2)) {
                $cita->apellidos_paciente = $request->pacienteApellido1." ".$request->pacienteApellido2;
            } else {
                $cita->apellidos_paciente = $request->pacienteApellido1;
            }

            if (!empty($request->represNombre2)) {
                $cita->nombres_representante = $request->represNombre1." ".$request->represNombre2;
            } else {
                $cita->nombres_representante = $request->represNombre1;
            }

            if (!empty($request->represApellido2)) {
                $cita->apellidos_representante = $request->represApellido1." ".$request->represApellido2;
            } else {
                $cita->apellidos_representante = $request->represApellido1;
            }

            $cita->numero_hc = $request->hc;
            $cita->fecha_hora = $request->fechaHora;
            $cita->estatus = 1;
            $cita->consulta_id = 1;

            if ($cita->save()) {
                return response()->json(['status' => 'exito', 'mensaje' => 'Cita agendada con exito']);
            } else {
                return response()->json(['status' => 'error', 'mensaje' => 'No se ha podido agendar la cita']);
            }
            
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error', 
                'mensaje' => 'Error de servidor o base de datos: '. $e->getMessage()
            ]);
        }
    }
}
