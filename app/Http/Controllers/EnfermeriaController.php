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

        if (empty($request->graObsReg)) {
            $errores['graObsReg'] = "Seleccione un tipo de gravedad de caso";
        }

        if (empty($request->descripObsReg)) {
            $errores['descripObsReg'] = "Debe escribir una descripcion de su observacion";
        }

        if (empty($request->accObsReg)) {
            $errores['accObsReg'] = "Debe describir las acciones realizadas";
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
                
               $medicinas = $request->input('medicinas', []);

                foreach ($medicinas as $medicinaId => $datos) {
                    $statusActual = $datos['status'];

                    $omision = ($statusActual == "0") ? ($datos['omision'] ?? null) : null;

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
}
