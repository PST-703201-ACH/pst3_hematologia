<?php

namespace App\Http\Controllers;

use App\Http\Requests\ConsultaMedicaRequest;
use App\Models\Cita;
use App\Models\Consulta;
use App\Models\Enfermedad;
use App\Models\Paciente;
use App\Models\Persona;
use App\Models\User;
use App\Models\Protocolo;
use App\Models\MedicinaProt;
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
                                ->orWhereRaw("CONCAT(nombres, ' ', apellidos) ILIKE ?", ["%{$busqueda}%"])
                                ->orWhere('cedula', 'like', "%{$busqueda}%")
                                ->orWhereRaw('fecha_hora::text ILIKE ?', ["%{$busqueda}%"]);
                        });
                })->orWhereHas('cita', function ($cita) use ($busqueda) {
                    $cita->where('numero_hc', 'like', "%{$busqueda}%")
                        ->orWhere('nombres_paciente', 'like', "%{$busqueda}%")
                        ->orWhere('apellidos_paciente', 'like', "%{$busqueda}%")
                        ->orWhereRaw('fecha_hora::text ILIKE ?', ["%{$busqueda}%"]);
                });
            });
        }

        if ($request->filled('fecha')) {
            $query->whereDate('fecha_hora', $request->input('fecha'));
        }

        return response()->json($query->get());
    }

    public function listarConR(Request $request)
    {
        $query = Consulta::with(['paciente.persona', 'enfermedad', 'cita'])->where('status', 1);

        if ($request->filled('busqueda')) {
            $busqueda = $request->string('busqueda')->trim();
            $query->where(function ($consulta) use ($busqueda) {
                $consulta->whereHas('paciente', function ($paciente) use ($busqueda) {
                    $paciente->where('hc', 'like', "%{$busqueda}%")
                        ->orWhereHas('persona', function ($persona) use ($busqueda) {
                            $persona->where('nombres', 'like', "%{$busqueda}%")
                                ->orWhere('apellidos', 'like', "%{$busqueda}%")
                                ->orWhereRaw("CONCAT(nombres, ' ', apellidos) ILIKE ?", ["%{$busqueda}%"])
                                ->orWhere('cedula', 'like', "%{$busqueda}%")
                                ->orWhereRaw('fecha_hora::text ILIKE ?', ["%{$busqueda}%"]);
                        });
                })->orWhereHas('cita', function ($cita) use ($busqueda) {
                    $cita->where('numero_hc', 'like', "%{$busqueda}%")
                        ->orWhere('nombres_paciente', 'like', "%{$busqueda}%")
                        ->orWhere('apellidos_paciente', 'like', "%{$busqueda}%")
                        ->orWhereRaw('fecha_hora::text ILIKE ?', ["%{$busqueda}%"]);
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

    public function registrarCon1(Request $request)
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
                $consulta->ante_personal = $request->antePersoConReg;
                $consulta->ante_familiar = $request->anteFamiConReg;
                $consulta->sintomas = $request->signosConReg;
                $consulta->subjetivo = $request->subjConReg;
                $consulta->plan_trabajo = $request->planConReg;
                $consulta->proxima_cita = $proxCita;
                $consulta->status = 1;

                if ($consulta->save()) {

                    $idPaciente = $consulta->paciente_id;

                    $cedulaPa = $request->nacPaConReg.'-'.$request->cedulaPaConReg;
                    $nombresPa = $request->nombrePa1ConReg.' '.$request->nombrePa2ConReg;
                    $apellidosPa = $request->apellidoPa1ConReg.' '.$request->apellidoPa2ConReg;
                    $sexoPa = $request->sexoPaConReg;
                    $naciPa = $request->naciPaConReg;
                    $emailPa = $request->emailPaConReg;
                    $telfPa = $request->tlfPaConReg;
                    $dirPa = $request->dirPaConReg;
                    $estadoPa = $request->estadoPaConReg;
                    $muniPa = $request->muniPaConReg;
                    $parroquiaPa = $request->parroquiaPaConReg;

                    $paciente = Paciente::where('paciente_id', $idPaciente)->with('persona')->first();

                    $paciente->persona->nombres = $nombresPa;
                    $paciente->persona->apellidos = $apellidosPa;
                    $paciente->persona->fecha_nacimiento = $naciPa;
                    $paciente->persona->sexo = $sexoPa;
                    $paciente->persona->cedula = $cedulaPa;
                    $paciente->persona->telefono = $telfPa;
                    $paciente->persona->email = $emailPa;
                    $paciente->persona->direccion_exacta = $dirPa;
                    $paciente->persona->estado_id = $estadoPa;
                    $paciente->persona->municipio_id = $muniPa;
                    $paciente->persona->parroquia_id = $parroquiaPa;

                    if ($paciente->persona->save()) {

                        $consultaInter = new Consulta;

                        $consultaInter->paciente_id = $paciente->paciente_id;
                        $consultaInter->enfermedad_id = $request->enfConReg;
                        $consultaInter->ante_personal = $request->antePersoConReg;
                        $consultaInter->ante_familiar = $request->anteFamiConReg;
                        $consultaInter->sintomas = $request->signosConReg;
                        $consultaInter->tipo = 2;
                        $consultaInter->fecha_hora = $proxCita;
                        $consultaInter->status = 0;

                        if ($consultaInter->save()) {

                            $citaAnterior = Cita::where('consulta_id', $consulta->consulta_id)->whereNotNull('nombres_representante')->first();
                            $cita = new Cita;

                            $cita->nombres_paciente = $nombresPa;
                            $cita->apellidos_paciente = $apellidosPa;
                            $cita->nombres_representante = $citaAnterior->nombres_representante;
                            $cita->apellidos_representante = $citaAnterior->apellidos_representante;
                            $cita->numero_hc = $paciente->hc;
                            $cita->fecha_hora = $proxCita;
                            $cita->consulta_id = $consultaInter->consulta_id;

                            if ($cita->save()) {
                                
                                DB::commit();

                                return response()->json([
                                    'status' => 'exito', 
                                    'mensaje' => 'Se ha registrado la consulta exitosamente'
                                ]);
                            }
                        }
                    }
                }
            }

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => 'error',
                'mensaje' => 'Error de servidor o base de datos: '. $e->getMessage()
            ]);
        }
    }

    public function precargarProts()
    {
        $protocolos = Protocolo::all(); 
        return response()->json($protocolos);
    }

    public function precargarMeds($id)
    {
        $medicinas = MedicinaProt::with('medicina')->where('id_protocolo', $id)->get(); 
        return response()->json($medicinas);
    }



    public function registrarCon2(Request $request)
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
                $consulta->ante_personal = $request->antePersoConReg;
                $consulta->ante_familiar = $request->anteFamiConReg;
                $consulta->sintomas = $request->signosConReg;
                $consulta->subjetivo = $request->subjConReg;
                $consulta->plan_trabajo = $request->planConReg;
                $consulta->proxima_cita = $proxCita;
                $consulta->status = 1;

                if ($consulta->save()) {

                    $idPaciente = $consulta->paciente_id;

                    $cedulaPa = $request->nacPaConReg.'-'.$request->cedulaPaConReg;
                    $nombresPa = $request->nombrePa1ConReg.' '.$request->nombrePa2ConReg;
                    $apellidosPa = $request->apellidoPa1ConReg.' '.$request->apellidoPa2ConReg;
                    $sexoPa = $request->sexoPaConReg;
                    $naciPa = $request->naciPaConReg;
                    $emailPa = $request->emailPaConReg;
                    $telfPa = $request->tlfPaConReg;
                    $dirPa = $request->dirPaConReg;
                    $estadoPa = $request->estadoPaConReg;
                    $muniPa = $request->muniPaConReg;
                    $parroquiaPa = $request->parroquiaPaConReg;

                    $paciente = Paciente::where('paciente_id', $idPaciente)->with('persona')->first();

                    $paciente->persona->nombres = $nombresPa;
                    $paciente->persona->apellidos = $apellidosPa;
                    $paciente->persona->fecha_nacimiento = $naciPa;
                    $paciente->persona->sexo = $sexoPa;
                    $paciente->persona->cedula = $cedulaPa;
                    $paciente->persona->telefono = $telfPa;
                    $paciente->persona->email = $emailPa;
                    $paciente->persona->estado_id = $estadoPa;
                    $paciente->persona->municipio_id = $muniPa;
                    $paciente->persona->parroquia_id = $parroquiaPa;

                    if ($paciente->save()) {

                        $consultaInter = new Consulta;

                        $consultaInter->paciente_id = $paciente->paciente_id;
                        $consultaInter->tipo = 2;
                        $consultaInter->fecha_hora = $proxCita;
                        $consultaInter->status = 0;

                        if ($consultaInter->save()) {

                            $citaAnterior = Cita::where('consulta_id', $consulta->consulta_id)->whereNotNull('nombres_representante')->first();
                            $cita = new Cita;

                            $cita->nombres_paciente = $nombresPa;
                            $cita->apellidos_paciente = $apellidosPa;
                            $cita->nombres_representante = $citaAnterior->nombres_representante;
                            $cita->apellidos_representante = $citaAnterior->apellidos_representante;
                            $cita->numero_hc = $paciente->hc;
                            $cita->fecha_hora = $proxCita;
                            $cita->consulta_id = $consultaInter->consulta_id;
                        }
                    }
                }
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
