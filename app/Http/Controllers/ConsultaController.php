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
use App\Models\MedicinaTrat;
use App\Models\Tratamiento;
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
        $consulta = Cita::with('consulta.paciente.persona', 'consulta.enfermedad')->where('cita_id', $id)->first();
        return response()->json($consulta);
    }

    public function registrarCon1(Request $request)
    {
        try {
            DB::beginTransaction();

            $errores = [];

            if (empty($request->enfConReg)) {
                $errores['enfConReg'] = "Complete esta informacion";
            }

            if ($request->pesoConReg === null || $request->pesoConReg === '') {
                $errores['pesoConReg'] = "Complete esta informacion";
            } elseif (!is_numeric($request->pesoConReg) || $request->pesoConReg <= 0) {
                $errores['pesoConReg'] = "Ingrese un valor numérico válido y mayor a 0";
            }

            if ($request->tallaConReg === null || $request->tallaConReg === '') {
                $errores['tallaConReg'] = "Complete esta informacion";
            } elseif (!is_numeric($request->tallaConReg) || $request->tallaConReg <= 0) {
                $errores['tallaConReg'] = "Ingrese un valor numérico válido y mayor a 0";
            }

            if ($request->scConReg === null || $request->scConReg === '') {
                $errores['scConReg'] = "Complete esta informacion";
            } elseif (!is_numeric($request->scConReg) || $request->scConReg <= 0) {
                $errores['scConReg'] = "Ingrese un valor numérico válido y mayor a 0";
            }

            if ($request->fcConReg === null || $request->fcConReg === '') {
                $errores['fcConReg'] = "Complete esta informacion";
            } elseif (!is_numeric($request->fcConReg) || $request->fcConReg < 0) {
                $errores['fcConReg'] = "Ingrese un valor numérico válido";
            }

            if ($request->frConReg === null || $request->frConReg === '') {
                $errores['frConReg'] = "Complete esta informacion";
            } elseif (!is_numeric($request->frConReg) || $request->frConReg < 0) {
                $errores['frConReg'] = "Ingrese un valor numérico válido";
            }

            if ($request->antePersoConReg === null || trim($request->antePersoConReg) === '') {
                $errores['antePersoConReg'] = "Complete esta informacion";
            } elseif (strlen($request->antePersoConReg) > 5000) {
                $errores['antePersoConReg'] = "El texto es demasiado largo (máximo 5000 caracteres)";
            }

            if ($request->anteFamiConReg === null || trim($request->anteFamiConReg) === '') {
                $errores['anteFamiConReg'] = "Complete esta informacion";
            } elseif (strlen($request->anteFamiConReg) > 5000) {
                $errores['anteFamiConReg'] = "El texto es demasiado largo (máximo 5000 caracteres)";
            }

            if ($request->signosConReg === null || trim($request->signosConReg) === '') {
                $errores['signosConReg'] = "Complete esta informacion";
            } elseif (strlen($request->signosConReg) > 5000) {
                $errores['signosConReg'] = "El texto es demasiado largo (máximo 5000 caracteres)";
            }

            if ($request->subjConReg === null || trim($request->subjConReg) === '') {
                $errores['subjConReg'] = "Complete esta informacion";
            } elseif (strlen($request->subjConReg) > 5000) {
                $errores['subjConReg'] = "El texto es demasiado largo (máximo 5000 caracteres)";
            }

            if ($request->planConReg === null || trim($request->planConReg) === '') {
                $errores['planConReg'] = "Complete esta informacion";
            } elseif (strlen($request->planConReg) > 5000) {
                $errores['planConReg'] = "El texto es demasiado largo (máximo 5000 caracteres)";
            }

            if (empty($request->citaFechaConReg)) {
                $errores['citaFechaConReg'] = "Complete esta informacion";
            }

            $fechaAgendada = new \DateTime($request->citaFechaConReg);
            $fechaActual = new \DateTime();

            if ($fechaAgendada <= $fechaActual) {
                $errores['citaFechaConReg'] = "La fecha debe ser después de la actual";
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

            if ($request->naciPaConReg === null || trim($request->naciPaConReg) === '') {

                $errores['naciPaConReg'] = "Complete esta informacion";
            }

                    $fechaNacimiento = new \DateTime($request->naciPaConReg);


                        $diferencia = $fechaActual->diff($fechaNacimiento);
                        $edad = $diferencia->y;

                        if ($edad >= 18) {
                            $errores['naciPaConReg'] = "El paciente debe ser menor de 18 años";
                        }
                    


            if ($request->cedulaPaConReg === null || trim($request->cedulaPaConReg) === '') {
                $errores['cedulaPaConReg'] = "Complete esta informacion";
            } elseif (!ctype_digit($request->cedulaPaConReg)) {
                $errores['cedulaPaConReg'] = "La cédula debe contener solo números";
            } elseif (strlen($request->cedulaPaConReg) < 7 || strlen($request->cedulaPaConReg) > 8) {
                $errores['cedulaPaConReg'] = "La cédula debe tener entre 7 y 8 dígitos";
            }

            if ($request->nombrePa1ConReg === null || trim($request->nombrePa1ConReg) === '') {
                $errores['nombrePa1ConReg'] = "Complete esta informacion";
            } elseif (strlen(trim($request->nombrePa1ConReg)) < 2) {
                $errores['nombrePa1ConReg'] = "Ingrese un nombre válido";
            }

            if ($request->nombrePa2ConReg !== null && trim($request->nombrePa2ConReg) !== '') {
                if (strlen(trim($request->nombrePa2ConReg)) < 2) {
                    $errores['nombrePa2ConReg'] = "Ingrese un nombre válido";
                }
            }

            if ($request->apellidoPa1ConReg === null || trim($request->apellidoPa1ConReg) === '') {
                $errores['apellidoPa1ConReg'] = "Complete esta informacion";
            } elseif (strlen(trim($request->apellidoPa1ConReg)) < 2) {
                $errores['apellidoPa1ConReg'] = "Ingrese un apellido válido";
            }

            if ($request->apellidoPa2ConReg !== null && trim($request->apellidoPa2ConReg) !== '') {
                if (strlen(trim($request->apellidoPa2ConReg)) < 2) {
                    $errores['apellidoPa2ConReg'] = "Ingrese un apellido válido";
                }
            }

            if (empty($request->sexoPaConReg)) {
                $errores['sexoPaConReg'] = "Complete esta informacion";
            }

            if ($request->emailPaConReg === null || trim($request->emailPaConReg) === '') {
                $errores['emailPaConReg'] = "Complete esta informacion";
            } elseif (!filter_var($request->emailPaConReg, FILTER_VALIDATE_EMAIL)) {
                $errores['emailPaConReg'] = "Ingrese una dirección de correo electrónico válida";
            }

            if ($request->tlfPaConReg === null || trim($request->tlfPaConReg) === '') {
                $errores['tlfPaConReg'] = "Complete esta informacion";
            } else {
                $tlfLimpio = trim($request->tlfPaConReg);
                
                if (!preg_match('/^[1-9][0-9]{6,14}$/', $tlfLimpio)) {

                    if (str_starts_with($tlfLimpio, '0')) {
                        $errores['tlfPaConReg'] = "Ingrese el número de teléfono sin el 0 a la izquierda";
                    } else {
                        $errores['tlfPaConReg'] = "Ingrese un número telefónico válido (solo números, entre 7 y 15 dígitos)";
                    }
                }
            }

            if ($request->dirPaConReg === null || trim($request->dirPaConReg) === '') {
                $errores['dirPaConReg'] = "Complete esta informacion";
            } elseif (strlen(trim($request->dirPaConReg)) < 10) {
                $errores['dirPaConReg'] = "Ingrese una dirección más detallada (mínimo 10 caracteres)";
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
                    "status" => "error",
                    "errores" => $errores,
                    "mensaje" => "Complete la informacion corectamente"
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
                $errores['enfConReg'] = "Complete esta informacion";
            }

            if ($request->pesoConReg === null || $request->pesoConReg === '') {
                $errores['pesoConReg'] = "Complete esta informacion";
            } elseif (!is_numeric($request->pesoConReg) || $request->pesoConReg <= 0) {
                $errores['pesoConReg'] = "Ingrese un valor numérico válido y mayor a 0";
            }

            if ($request->tallaConReg === null || $request->tallaConReg === '') {
                $errores['tallaConReg'] = "Complete esta informacion";
            } elseif (!is_numeric($request->tallaConReg) || $request->tallaConReg <= 0) {
                $errores['tallaConReg'] = "Ingrese un valor numérico válido y mayor a 0";
            }

            if ($request->scConReg === null || $request->scConReg === '') {
                $errores['scConReg'] = "Complete esta informacion";
            } elseif (!is_numeric($request->scConReg) || $request->scConReg <= 0) {
                $errores['scConReg'] = "Ingrese un valor numérico válido y mayor a 0";
            }

            if ($request->fcConReg === null || $request->fcConReg === '') {
                $errores['fcConReg'] = "Complete esta informacion";
            } elseif (!is_numeric($request->fcConReg) || $request->fcConReg < 0) {
                $errores['fcConReg'] = "Ingrese un valor numérico válido";
            }

            if ($request->frConReg === null || $request->frConReg === '') {
                $errores['frConReg'] = "Complete esta informacion";
            } elseif (!is_numeric($request->frConReg) || $request->frConReg < 0) {
                $errores['frConReg'] = "Ingrese un valor numérico válido";
            }

            if ($request->antePersoConReg === null || trim($request->antePersoConReg) === '') {
                $errores['antePersoConReg'] = "Complete esta informacion";
            } elseif (strlen($request->antePersoConReg) > 5000) {
                $errores['antePersoConReg'] = "El texto es demasiado largo (máximo 5000 caracteres)";
            }

            if ($request->anteFamiConReg === null || trim($request->anteFamiConReg) === '') {
                $errores['anteFamiConReg'] = "Complete esta informacion";
            } elseif (strlen($request->anteFamiConReg) > 5000) {
                $errores['anteFamiConReg'] = "El texto es demasiado largo (máximo 5000 caracteres)";
            }

            if ($request->signosConReg === null || trim($request->signosConReg) === '') {
                $errores['signosConReg'] = "Complete esta informacion";
            } elseif (strlen($request->signosConReg) > 5000) {
                $errores['signosConReg'] = "El texto es demasiado largo (máximo 5000 caracteres)";
            }

            if ($request->subjConReg === null || trim($request->subjConReg) === '') {
                $errores['subjConReg'] = "Complete esta informacion";
            } elseif (strlen($request->subjConReg) > 5000) {
                $errores['subjConReg'] = "El texto es demasiado largo (máximo 5000 caracteres)";
            }

            if ($request->planConReg === null || trim($request->planConReg) === '') {
                $errores['planConReg'] = "Complete esta informacion";
            } elseif (strlen($request->planConReg) > 5000) {
                $errores['planConReg'] = "El texto es demasiado largo (máximo 5000 caracteres)";
            }

            if (empty($request->citaFechaConReg)) {
                $errores['citaFechaConReg'] = "Complete esta informacion";
            }

            $fechaAgendada = new \DateTime($request->citaFechaConReg);
            $fechaActual = new \DateTime();

            if ($fechaAgendada <= $fechaActual) {
                $errores['citaFechaConReg'] = "La fecha debe ser después de la actual";
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

            if ($request->naciPaConReg === null || trim($request->naciPaConReg) === '') {

                $errores['naciPaConReg'] = "Complete esta informacion";
            }

            $fechaNacimiento = new \DateTime($request->naciPaConReg);

            $diferencia = $fechaActual->diff($fechaNacimiento);
            $edad = $diferencia->y;

            if ($edad >= 18) {
                $errores['naciPaConReg'] = "El paciente debe ser menor de 18 años";
            }
                    
            if ($request->cedulaPaConReg === null || trim($request->cedulaPaConReg) === '') {
                $errores['cedulaPaConReg'] = "Complete esta informacion";
            } elseif (!ctype_digit($request->cedulaPaConReg)) {
                $errores['cedulaPaConReg'] = "La cédula debe contener solo números";
            } elseif (strlen($request->cedulaPaConReg) < 7 || strlen($request->cedulaPaConReg) > 8) {
                $errores['cedulaPaConReg'] = "La cédula debe tener entre 7 y 8 dígitos";
            }

            if ($request->nombrePa1ConReg === null || trim($request->nombrePa1ConReg) === '') {
                $errores['nombrePa1ConReg'] = "Complete esta informacion";
            } elseif (strlen(trim($request->nombrePa1ConReg)) < 2) {
                $errores['nombrePa1ConReg'] = "Ingrese un nombre válido";
            }

            if ($request->nombrePa2ConReg !== null && trim($request->nombrePa2ConReg) !== '') {
                if (strlen(trim($request->nombrePa2ConReg)) < 2) {
                    $errores['nombrePa2ConReg'] = "Ingrese un nombre válido";
                }
            }

            if ($request->apellidoPa1ConReg === null || trim($request->apellidoPa1ConReg) === '') {
                $errores['apellidoPa1ConReg'] = "Complete esta informacion";
            } elseif (strlen(trim($request->apellidoPa1ConReg)) < 2) {
                $errores['apellidoPa1ConReg'] = "Ingrese un apellido válido";
            }

            if ($request->apellidoPa2ConReg !== null && trim($request->apellidoPa2ConReg) !== '') {
                if (strlen(trim($request->apellidoPa2ConReg)) < 2) {
                    $errores['apellidoPa2ConReg'] = "Ingrese un apellido válido";
                }
            }

            if (empty($request->sexoPaConReg)) {
                $errores['sexoPaConReg'] = "Complete esta informacion";
            }

            if ($request->emailPaConReg === null || trim($request->emailPaConReg) === '') {
                $errores['emailPaConReg'] = "Complete esta informacion";
            } elseif (!filter_var($request->emailPaConReg, FILTER_VALIDATE_EMAIL)) {
                $errores['emailPaConReg'] = "Ingrese una dirección de correo electrónico válida";
            }

            if ($request->tlfPaConReg === null || trim($request->tlfPaConReg) === '') {
                $errores['tlfPaConReg'] = "Complete esta informacion";
            } else {
                $tlfLimpio = trim($request->tlfPaConReg);
                
                if (!preg_match('/^[1-9][0-9]{6,14}$/', $tlfLimpio)) {

                    if (str_starts_with($tlfLimpio, '0')) {
                        $errores['tlfPaConReg'] = "Ingrese el número de teléfono sin el 0 a la izquierda";
                    } else {
                        $errores['tlfPaConReg'] = "Ingrese un número telefónico válido (solo números, entre 7 y 15 dígitos)";
                    }
                }
            }

            if ($request->dirPaConReg === null || trim($request->dirPaConReg) === '') {
                $errores['dirPaConReg'] = "Complete esta informacion";
            } elseif (strlen(trim($request->dirPaConReg)) < 10) {
                $errores['dirPaConReg'] = "Ingrese una dirección más detallada (mínimo 10 caracteres)";
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

            if (!$request->has('protocolos') || empty($request->input('protocolos'))) {
                return response()->json([
                    "status" => "error",
                    "mensaje" => "Establezca un tratamiento en la pagina 4",
                ]);
            }

            $datosProtocolos = $request->input('protocolos');

            foreach ($datosProtocolos as $protocoloId => $contenido) {
                $medicinas = $contenido['medicinas'] ?? [];
                $vias = $contenido['vias'] ?? [];

                foreach ($medicinas as $medicinaId => $nombreMedicina) {
                    $viaSeleccionada = $vias[$medicinaId] ?? null;

                    if ($viaSeleccionada === null || trim($viaSeleccionada) === '') {
                        $errores['via'] = "error"; 
                    }
                }
            }

            if (!empty($errores)) {
                return response()->json([
                    "status" => "error",
                    "errores" => $errores,
                    "mensaje" => "Complete la informacion correctamente"
                ]);
                exit;
            }

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

                    $request->validate([
                        'protocolos' => 'required|array',
                    ]);

                    $tratamiento = Tratamiento::create([
                        'status' => 0
                    ]);

                    $datosProtocolos = $request->input('protocolos');

                    foreach ($datosProtocolos as $protocolo => $contenido) {
                        
                        $medicinas = $contenido['medicinas'] ?? [];
                        $vias = $contenido['vias'] ?? [];

                        foreach ($medicinas as $medicina => $nombreMedicina) {
                            $via = $vias[$medicina] ?? null;

                            MedicinaTrat::create([
                                'paciente_id' => $paciente->paciente_id,
                                'consulta_id' => $consulta->consulta_id,
                                'protocolo_id' => $protocolo,
                                'medico_id' => $request->usuConReg,
                                'medicina_id' => $medicina,
                                'tratamiento_id' => $tratamiento->tratamiento_id,
                                'via_admin' => $via,
                                'status' => 0,
                            ]);
                        }
                    }

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

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => 'error',
                'mensaje' => 'Error de servidor o base de datos: '. $e->getMessage()
            ]);
        }
    }

    public function ver($id) {
        $detalles = Cita::with('consulta', 'consulta.paciente', 'consulta.medico', 'consulta.enfermedad', 'consulta.medico.persona', 'consulta.paciente.persona')->where('cita_id', $id)->first();
        return response()->json($detalles);
    }
}
