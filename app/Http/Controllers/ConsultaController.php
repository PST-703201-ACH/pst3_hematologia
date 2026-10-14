<?php

namespace App\Http\Controllers;

use App\Http\Requests\ConsultaMedicaRequest;
use App\Models\Cita;
use App\Models\Consulta;
use App\Models\Enfermedad;
use App\Models\Paciente;
use App\Models\Persona;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ConsultaController extends Controller
{
    public function listarConP(Request $request)
    {
        $query = Consulta::with(['paciente.persona', 'enfermedad', 'cita'])->where('status', 0);

        if ($request->filled('busqueda')) {
            $busqueda = $request->string('busqueda')->trim();
            $query->where(function ($consulta) use ($busqueda) {
                $consulta->whereHas('paciente', function ($paciente) use ($busqueda) {
                    $paciente->where('hc', 'like', "%{$busqueda}%")
                        ->orWhereHas('persona', function ($persona) use ($busqueda) {
                            $persona->where('nombres', 'like', "%{$busqueda}%")
                                ->orWhere('apellidos', 'like', "%{$busqueda}%")
                                ->orWhere('cedula', 'like', "%{$busqueda}%");
                        });
                })->orWhereHas('cita', function ($cita) use ($busqueda) {
                    $cita->where('numero_hc', 'like', "%{$busqueda}%")
                        ->orWhere('nombres_paciente', 'like', "%{$busqueda}%")
                        ->orWhere('apellidos_paciente', 'like', "%{$busqueda}%");
                });
            });
        }

        if ($request->filled('fecha')) {
            $query->whereDate('fecha_hora', $request->input('fecha'));
        }

        return response()->json($query->get());
    }

    public function obtenerEnf(){
        $enfermedades = Enfermedad::all(); 
        return response()->json($enfermedades);
    }

    public function precargarConP($id)
    {
        $consulta = Cita::with('consulta.paciente.persona')->where('cita_id', $id)->first();
        return response()->json($consulta);
    }

    public function registrarCon(Request $request)
    {
        try {
            DB::beginTransaction();

            $errores = [];

            if (empty($request->enfConReg)) {
                $errores['enfConReg'] = "";
            }

            if (empty($request->pesoConReg)) {
                $errores['pesoConReg'] = "Complete esta informacion";
            }

            if (empty($request->tallaConReg)) {
                $errores['tallaConReg'] = "Complete esta informacion";
            }

            if (empty($request->scConReg)) {
                $errores['scConReg'] = "Complete esta informacion";
            }

            if (empty($request->fcConReg)) {
                $errores['fcConReg'] = "Complete esta informacion";
            }

            if (empty($request->frConReg)) {
                $errores['frConReg'] = "Complete esta informacion";
            }

            if (empty($request->antePersoConReg)) {
                $errores['antePersoConReg'] = "Complete esta informacion";
            }

            if (empty($request->anteFamiConReg)) {
                $errores['anteFamiConReg'] = "Complete esta informacion";
            }

            if (empty($request->signosConReg)) {
                $errores['signosConReg'] = "Complete esta informacion";
            }

            if (empty($request->subjConReg)) {
                $errores['subjConReg'] = "Complete esta informacion";
            }

            if (empty($request->planConReg)) {
                $errores['planConReg'] = "Complete esta informacion";
            }

            if (empty($request->citaFechaConReg)) {
                $errores['citaFechaConReg'] = "Complete esta informacion";
            }

            if (empty($request->citaHoraConReg)) {
                $errores['citaHoraConReg'] = "Complete esta informacion";
            }

            if (empty($request->hcPaConReg)) {
                $errores['hcPaConReg'] = "Complete esta informacion";
            }

            if (empty($request->nacPaConReg)) {
                $errores['nacPaConReg'] = "Complete esta informacion";
            }

            if (empty($request->cedulaPaConReg)) {
                $errores['cedulaPaConReg'] = "Complete esta informacion";
            }

            if (empty($request->nombrePa1ConReg)) {
                $errores['nombrePa1ConReg'] = "Complete esta informacion";
            }

            if (empty($request->nombrePa2ConReg)) {
                $errores['nombrePa2ConReg'] = "Complete esta informacion";
            }

            if (empty($request->apellidoPa1ConReg)) {
                $errores['apellidoPa1ConReg'] = "Complete esta informacion";
            }

            if (empty($request->apellidoPa2ConReg)) {
                $errores['apellidoPa2ConReg'] = "Complete esta informacion";
            }

            if (empty($request->sexoPaConReg)) {
                $errores['sexoPaConReg'] = "Complete esta informacion";
            }

            if (empty($request->emailPaConReg)) {
                $errores['emailPaConReg'] = "Complete esta informacion";
            }

            if (empty($request->tlfPaConReg)) {    
                $errores['tlfPaConReg'] = "Complete esta informacion";
            }

            if (empty($request->dirPaConReg)) {
                $errores['dirPaConReg'] = "Complete esta informacion";
            }

            if (empty($request->estadoPaConReg)) {
                $errores['estadoPaConReg'] = "Complete esta informacion";
            }

            if (empty($request->muniPaConReg)) {
                $errores['muniPaConReg'] = "Complete esta informacion";
            }

            if (empty($request->parroquiaPaConReg)) {
                $errores['parroquiaPaConReg'] = "Complete esta informacion";
            }


            if (!empty($errores)) {
                return response()->json([
                    "status" => "errores",
                    "errores" => $errores
                ]);
                exit;
            } else {

                $tiempoActual = new \DateTime();
                $fechaHora = $tiempoActual->format('Y-m-d H:i:s');
                $fechaHoraProx = new \DateTime("$request->citaFechaConReg $request->citaHoraConReg");
                $proxCita = $fechaHoraProx->format('Y-m-d H:i:s');

                $consulta = Consulta::with('paciente.persona')->where('consulta_id', $request->consultaReg)->first();

                $consulta->medico_id = $request->usuConReg;
                $consulta->enfermedad_id = $request->enfConReg;
                $consulta->fecha_hora = $fechaHora;
                $consulta->peso = $request->pesoConReg;
                $consulta->talla = $request->tallaConReg;
                $consulta->sc = $request->scConReg;
                $consulta->fc = $request->fcConReg;
                $consulta->fr = $request->frConReg;
                $consulta->subjetivo = $request->subjConReg;
                $consulta->plan_trabajo = $request->planConReg;
                $consulta->proxima_cita = $proxCita;
                $consulta->status = 1;
            }

            DB::commit();

            return response()->json([
                'status' => 'exito', 
                'mensaje' => 'Se ha registrado la consulta exitosamente'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => 'error',
                'mensaje' => 'Error de servidor o base de datos: '. $e->getMessage()
            ]);
        }
    }
}
