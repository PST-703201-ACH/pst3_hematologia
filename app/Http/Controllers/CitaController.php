<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cita;
use App\Models\Consulta;
use App\Models\Paciente;
use App\Models\Persona;

class CitaController extends Controller
{
    public function agendar(Request $request)
    {
        $errores = [];

        if (empty($request->pacienteNombre1)) {
            $errores['pacienteNombre1'] = "Solo debe ingresar el primer nombre";
        }

        if (empty($request->pacienteApellido1)) {
            $errores['pacienteApellido1'] = "Solo debe ingresar el segundo nombre";
        }

        if (empty($request->represNombre1)) {
            $errores['represNombre1'] = "Solo debe ingresar el primer apellido";
        }

        if (empty($request->represApellido1)) {
            $errores['represApellido1'] = "Solo debe ingresar el segundo apellido";
        }

        if (empty($request->hc)) {
            $errores['hc'] = "Debe ingresar el Nº de historia clinica del nuevo paciente";
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
            $persona = new Persona();
            
            if (!empty($request->pacienteNombre2)) {
                $persona->nombres = $request->pacienteNombre1." ".$request->pacienteNombre2;
            } else {
                $persona->nombres = $request->pacienteNombre1;
            }

            if (!empty($request->pacienteApellido2)) {
                $persona->apellidos = $request->pacienteApellido1." ".$request->pacienteApellido2;
            } else {
                $persona->apellidos = $request->pacienteApellido1;
            }

            if ($persona->save()) {
                $paciente = new Paciente();
                $paciente->persona_id = $persona->persona_id;
                $paciente->hc = $request->hc;
                $paciente->status = 0;

                if ($paciente->save()) {
                    $consulta = new Consulta();
                    $consulta->paciente_id = $paciente->paciente_id;
                    $consulta->status = 0;
                    if ($consulta->save()) {
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
                        $cita->fecha_hora = $request->fechaCita.' '.$request->fechaHora.':00';
                        $cita->consulta_id = $consulta->consulta_id;

                        if ($cita->save()) {
                            return response()->json(['status' => 'exito', 'mensaje' => 'Cita agendada con exito']);
                        } else {
                            return response()->json(['status' => 'error', 'mensaje' => 'No se ha podido agendar la cita']);
                        }
                    }
                }
            }

            
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error', 
                'mensaje' => 'Error de servidor o base de datos: '. $e->getMessage()
            ]);
        }
    }

    public function precargar($id){
        $cita = Cita::where('cita_id', $id)->first();
        return response()->json($cita);
    }

    public function reprogramar(Request $request)
    {
        $errores = [];

        if (empty($request->pacienteNombreRep1)) {
            $errores['pacienteNombreRep1'] = "Solo debe ingresar el primer nombre";
        }

        if (empty($request->pacienteApellidoRep1)) {
            $errores['pacienteApellidoRep1'] = "Solo debe ingresar el segundo nombre";
        }

        if (empty($request->represNombreRep1)) {
            $errores['represNombreRep1'] = "Solo debe ingresar el primer apellido";
        }

        if (empty($request->represApellidoRep1)) {
            $errores['represApellidoRep1'] = "Solo debe ingresar el segundo apellido";
        }

        if (empty($request->hcRep)) {
            $errores['hcRep'] = "Debe ingresar el Nº de historia clinica del nuevo paciente";
        }

        if (empty($request->fechaHoraRep)) {
            $errores['fechaHoraRep'] = "Debe ingresar la hora para la cita";
        }

        if (!empty($errores)) {
            return response()->json([
                "status" => "errores",
                "errores" => $errores
            ]);
            exit;
        }

        try {
            $hc = $request->hcRep;

            $persona = Persona::whereHas('paciente', function ($query) use ($hc) {
                $query->where('hc', $hc);
            })
            ->first();

            if ($persona) {
                $pacienteId = $persona->paciente->paciente_id;
            }

            if (!empty($request->pacienteNombreRep2)) {
                $nombres = $request->pacienteNombreRep1." ".$request->pacienteNombreRep2;
            } else {
                $nombres = $request->pacienteNombreRep1;
            }

            if (!empty($request->pacienteApellidoRep2)) {
                $apellidos = $request->pacienteApellidoRep1." ".$request->pacienteApellidoRep2;
            } else {
                $apellidos = $request->pacienteApellidoRep1;
            }

            if ($persona->update([
                'nombres' => $nombres,
                'apellidos' => $apellidos
            ])) {
                $consulta = Consulta::where('paciente_id', $pacienteId)->first();
                $consultaId = $consulta->consulta_id;
            }

            if ($consulta->update([
                'fecha_hora' => $request->fechaCitaRep.' '.$request->fechaHoraRep.':00'])) {
                $cita = Cita::where('consulta_id', $consultaId);
            }

            if (!empty($request->represNombreRep2)) {
                $nombresR = $request->represNombreRep1." ".$request->represNombreRep2;
            } else {
                $nombresR = $request->represNombreRep1;
            }

            if (!empty($request->represApellidoRep2)) {
                $apellidosR = $request->represApellidoRep1." ".$request->represApellidoRep2;
            } else {
                $apellidosR = $request->represApellidoRep1;
            }

            if ($cita->update([
                'nombres_paciente' => $nombres,
                'apellidos_paciente' => $apellidos,
                'nombres_representante' => $nombresR,
                'apellidos_representante' => $apellidosR,
                'fecha_hora' => $request->fechaCitaRep.' '.$request->fechaHoraRep.':00'])) {
                return response()->json(['status' => 'exito', 'mensaje' => 'Cita reprogramada con exito']);
            } else {
                return response()->json(['status' => 'error', 'mensaje' => 'No se ha podido reprogramar la cita']);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error', 
                'mensaje' => 'Error de servidor o base de datos: '. $e->getMessage()
            ]);
        }
    }

    public function getCitas(Request $request)
    {
        $citas = Cita::get();
        
        return response()->json($citas);
    }


}
