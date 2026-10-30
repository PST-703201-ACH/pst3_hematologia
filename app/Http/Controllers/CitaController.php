<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cita;
use App\Models\Consulta;
use App\Models\Paciente;
use App\Models\Persona;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

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

        $hcExistente = Paciente::where('hc', $request->hc)->exists();

        if ($hcExistente) {
            $errores['hc'] = "Nº de historia clinica no disponible";
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

    public function listarCitas(Request $request)
    {
        $citas = Cita::with('consulta')->get();
        
        return response()->json($citas);
    }

    public function exportarPdf()
    {
        $citas = $this->filasExportacion();
        $fechaEmision = Carbon::now();

        return Pdf::loadView('reportes.citas_pdf', [
            'citas' => $citas,
            'fecha_emision' => $fechaEmision->format('d/m/Y H:i'),
        ])->setPaper('a4', 'landscape')
            ->download('reporte_citas_' . $fechaEmision->format('Ymd_His') . '.pdf');
    }

    public function exportarExcel()
    {
        $citas = $this->filasExportacion();
        $nombreArchivo = 'reporte_citas_' . Carbon::now()->format('Ymd_His') . '.csv';

        return response()->streamDownload(function () use ($citas) {
            $salida = fopen('php://output', 'w');

            if ($salida === false) {
                throw new \RuntimeException('No se pudo generar el archivo de citas.');
            }

            if (fwrite($salida, "\xEF\xBB\xBF") === false || fputcsv($salida, [
                'Fecha y hora (cita)',
                'Número de historia clínica',
                'Nombre del paciente',
                'Nombre del representante',
                'Consulta asociada',
                'Citas registradas en la fecha',
            ], ';', '"', '\\') === false) {
                throw new \RuntimeException('No se pudo escribir el archivo de citas.');
            }

            foreach ($citas as $cita) {
                $valores = [
                    $cita['fecha_hora'],
                    $cita['numero_hc'],
                    $cita['paciente'],
                    $cita['representante'],
                    $cita['consulta'],
                    $cita['conteo_fecha'],
                ];
                $valores = array_map(static function ($valor) {
                    $valor = (string) $valor;

                    return preg_match('/^[=+\-@\t\r]/', $valor) === 1 ? "'" . $valor : $valor;
                }, $valores);

                if (fputcsv($salida, $valores, ';', '"', '\\') === false) {
                    throw new \RuntimeException('No se pudo escribir una cita en el archivo.');
                }
            }

            fclose($salida);
        }, $nombreArchivo, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    private function filasExportacion(): Collection
    {
        $citas = Cita::with('consulta.enfermedad')
            ->orderBy('fecha_hora')
            ->get();

        $conteosPorFecha = $citas->countBy(function (Cita $cita) {
            return $cita->fecha_hora
                ? Carbon::parse($cita->fecha_hora)->toDateString()
                : 'sin-fecha';
        });

        return $citas->map(function (Cita $cita) use ($conteosPorFecha) {
            $fecha = $cita->fecha_hora ? Carbon::parse($cita->fecha_hora) : null;
            $consulta = $cita->consulta;
            $consultaAsociada = $consulta
                ? 'Consulta #' . $consulta->consulta_id
                : 'Sin consulta asociada';

            if ($consulta?->enfermedad?->descripcion) {
                $consultaAsociada .= ' - ' . $consulta->enfermedad->descripcion;
            }

            return [
                'fecha_hora' => $fecha?->format('d/m/Y H:i') ?? 'Sin fecha',
                'numero_hc' => $cita->numero_hc ?? 'No registrado',
                'paciente' => trim(($cita->nombres_paciente ?? '') . ' ' . ($cita->apellidos_paciente ?? '')) ?: 'No registrado',
                'representante' => trim(($cita->nombres_representante ?? '') . ' ' . ($cita->apellidos_representante ?? '')) ?: 'No registrado',
                'consulta' => $consultaAsociada,
                'conteo_fecha' => $conteosPorFecha[$fecha?->toDateString() ?? 'sin-fecha'],
            ];
        });
    }


}
