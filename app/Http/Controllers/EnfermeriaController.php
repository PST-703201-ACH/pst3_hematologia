<?php

namespace App\Http\Controllers;

use App\Http\Requests\Enfermeria\AplicarSesionRequest;
use App\Http\Requests\Enfermeria\AplicarTransfusionRequest;
use App\Http\Requests\Enfermeria\PanelEnfermeriaRequest;
use App\Http\Requests\Enfermeria\StoreObservacionRequest;
use App\Http\Requests\Enfermeria\StoreReaccionAdversaRequest;
use App\Models\Auditoria;
use App\Models\MedicinaTrat;
use App\Models\Observacion;
use App\Models\Tratamiento;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class EnfermeriaController extends Controller
{

    public function listarJor(Request $request) {
        $query = Tratamiento::with('medicina.paciente', 'medicina.paciente.persona')->where('status', 0);

        if ($request->filled('busqueda')) {
            $termino = $request->input('busqueda');
            $query->where(function($q) use ($termino) {
                $q->WhereHas('medicina', function ($mQ) use ($termino) {
                    $mQ->WhereHas('paciente', function ($pQ) use ($termino) {
                        $pQ->where('hc', 'ILIKE', "%{$termino}%");
                        $pQ->orwhereHas('persona', function ($peQ) use ($termino) {
                            $peQ->where('nombres', 'ILIKE', "%{$termino}%")
                               ->orWhere('apellidos', 'ILIKE', "%{$termino}%")
                               ->orWhereRaw("CONCAT(nombres, ' ', apellidos) ILIKE ?", ["%{$termino}%"]);
                        });
                    });
                });
            });
        }

        if ($request->filled('status')) {
            $status = $request->input('status');
            $query->where('status', $status);
        }

        return response()->json($query->get());
    }

    public function precargarTrat($id){
        $tratamiento = Tratamiento::with('medicina.paciente', 'medicina.paciente.persona', 'medicina.medicina')->where('tratamiento_id', $id)->first();
        return response()->json($tratamiento);
    }

    public function registrarObs(Request $request) {
        $errores = [];

        if ($request->graObsReg === '') {
            $errores['graObsReg'] = "Seleccione un tipo de gravedad de caso";
        }

        $descripObsReg = trim($request->descripObsReg ?? '');
        $accObsReg = trim($request->accObsReg ?? '');

        $MIN_CARACTERES = 10;
        $MAX_CARACTERES = 2000;

        if (empty($descripObsReg)) {
            $errores['descripObsReg'] = "Debe escribir una descripción de su observación.";
        } else {
            $longitudDesc = mb_strlen($descripObsReg, 'UTF-8');
            
            if ($longitudDesc < $MIN_CARACTERES) {
                $errores['descripObsReg'] = "La descripción es muy corta. Debe tener al menos {$MIN_CARACTERES} caracteres.";
            } elseif ($longitudDesc > $MAX_CARACTERES) {
                $errores['descripObsReg'] = "La descripción supera el límite permitido de {$MAX_CARACTERES} caracteres (actual: {$longitudDesc}).";
            }
        }

        if (empty($accObsReg)) {
            $errores['accObsReg'] = "Debe describir las acciones realizadas.";
        } else {
            $longitudAcc = mb_strlen($accObsReg, 'UTF-8');
            
            if ($longitudAcc < $MIN_CARACTERES) {
                $errores['accObsReg'] = "La descripción de acciones es muy corta. Debe tener al menos {$MIN_CARACTERES} caracteres.";
            } elseif ($longitudAcc > $MAX_CARACTERES) {
                $errores['accObsReg'] = "Las acciones superan el límite permitido de {$MAX_CARACTERES} caracteres (actual: {$longitudAcc}).";
            }
        }

        if (empty($errores)) {
            $descripObsRegLimpio = htmlspecialchars($descripObsReg, ENT_QUOTES, 'UTF-8');
            $accObsRegLimpio = htmlspecialchars($accObsReg, ENT_QUOTES, 'UTF-8');
        }


        if (!empty($errores)) {
            return response()->json([
                "status" => "errores",
                "errores" => $errores
            ]);
            exit;
        } else {

            try {

                $tiempoActual = new \DateTime();
                $fechaHora = $tiempoActual->format('Y-m-d H:i:s');
                
                $medicinas = $request->input('medicinas');

                if (empty($medicinas)) {
                    return response()->json([
                        "status" => "error",
                        "mensaje" => "Error, no ha seleccionado la informacion de cumplimiento de tratamiento"
                    ], 400);
                }

                $medicinas = $request->input('medicinas');

                if (empty($medicinas) || !is_array($medicinas)) {
                    return response()->json([
                        "status" => "error",
                        "mensaje" => "Error, no ha seleccionado la información de cumplimiento de tratamiento"
                    ], 400);
                }

                $medicinasValidas = array_filter($medicinas, function($datos) {
                    if (!isset($datos['status'])) return false;
                    return $datos['status'] == '1' || $datos['status'] == '0';
                });

                $cantidadEnviada = count($medicinasValidas);

                $cantidadEsperada = MedicinaTrat::where('tratamiento_id', $request->tratObsReg)->count();

                if ($cantidadEnviada !== $cantidadEsperada) {
                    return response()->json([
                        "status" => "error",
                        "mensaje" => "Error, la cantidad de medicinas evaluadas no coincide con el tratamiento"
                    ], 400);
                }

                $erroresGrupales = [];

                foreach ($medicinasValidas as $medicinaId => $datos) {
                    $statusActual = $datos['status'];

                    if ($statusActual === "0") {
                        $omision = $datos['omision'] ?? null;
                        $longitud = mb_strlen(trim($omision), 'UTF-8');

                        if ($longitud === 0) {
                            return response()->json([
                                "status" => "error",
                                "mensaje" => "Error, debe justificar todos los medicamentos omitidos"
                            ], 400);
                        } elseif ($longitud > 500) {
                            return response()->json([
                                "status" => "error",
                                "mensaje" => "Error, la justificacion es muy larga, no debe superar los 500 caracteres"
                            ], 400);
                        }
                    }
                }

                if (!empty($erroresGrupales)) {
                    return response()->json([
                        "status" => "errores",
                        "errores" => $erroresGrupales
                    ], 422);
                }

                foreach ($medicinasValidas as $medicinaId => $datos) {
                    $statusActual = $datos['status'];
                    $omision = ($statusActual == "0") ? trim($datos['omision']) : null;

                    MedicinaTrat::where('id', $medicinaId)->update([
                        'status' => $statusActual,
                        'omision' => $omision
                    ]);
                }

                $tratamiento = Tratamiento::where('tratamiento_id', $request->tratObsReg)->first();
                $tratamiento->status = 1;

                if ($tratamiento->save()) {

                    $observacion = new Observacion;
                    $observacion->paciente_id = $request->paObsReg;
                    $observacion->enfermero_id = $request->usuObsReg;
                    $observacion->tratamiento_id = $request->tratObsReg;
                    $observacion->gravedad = $request->graObsReg;
                    $observacion->fecha_hora = $fechaHora;
                    $observacion->descripcion = $request->descripObsReg;
                    $observacion->acciones = $request->accObsReg;

                    if ($observacion->save()) {
                        return response()->json(['status' => 'exito', 'mensaje' => 'Se ha registrado la medicina exitosamente']);    
                    }
                }
                
            } catch (\Exception $e) {
                return response()->json([
                    'status' => 'error',
                    'mensaje' => 'Error de servidor o base de datos: '. $e->getMessage()
                ]);
            }
        }
    }

    public function listarObs(Request $request) {
        $query = Observacion::with('paciente', 'enfermero', 'paciente.persona', 'enfermero.persona');

        if ($request->filled('busqueda')) {
            $termino = $request->input('busqueda');
            
            $query->where(function($q) use ($termino) {
                $q->whereHas('paciente', function ($pQ) use ($termino) {
                    $pQ->where('hc', 'ILIKE', "%{$termino}%");
                    
                    $pQ->orWhereHas('persona', function ($peQ) use ($termino) {
                        $peQ->where('nombres', 'ILIKE', "%{$termino}%")
                           ->orWhere('apellidos', 'ILIKE', "%{$termino}%")
                           ->orWhereRaw("CONCAT(nombres, ' ', apellidos) ILIKE ?", ["%{$termino}%"]);
                    });
                })->orWhereHas('enfermero', function ($eQ) use ($termino) {
                    $eQ->whereHas('persona', function ($peQ) use ($termino) {
                        $peQ->where('nombres', 'ILIKE', "%{$termino}%") 
                            ->orWhere('apellidos', 'ILIKE', "%{$termino}%")
                            ->orWhereRaw("CONCAT(nombres, ' ', apellidos) ILIKE ?", ["%{$termino}%"]);
                    });
                });
            });
        }

        if ($request->filled('gravedad')) {
            $gravedad = $request->input('gravedad');
            $query->where('gravedad', $gravedad);
        }

        if ($request->filled('fecha')) {
            $query->whereDate('fecha_hora', $request->input('fecha'));
        }

        return response()->json($query->get());
    }

    public function verObs($id){
        $detalles = Observacion::with('tratamiento', 'enfermero', 'paciente', 'tratamiento.medicina.medicina', 'enfermero.persona', 'paciente.persona', 'tratamiento.medicina.medico.persona', 'tratamiento.medicina.consulta.enfermedad')->where('observacion_id', $id)->get();
        return response()->json($detalles);
    }
}
