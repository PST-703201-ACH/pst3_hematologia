<?php

namespace App\Http\Controllers;

use App\Http\Requests\Enfermeria\AplicarSesionRequest;
use App\Http\Requests\Enfermeria\AplicarTransfusionRequest;
use App\Http\Requests\Enfermeria\PanelEnfermeriaRequest;
use App\Http\Requests\Enfermeria\StoreObservacionRequest;
use App\Http\Requests\Enfermeria\StoreReaccionAdversaRequest;
use App\Models\Auditoria;
use App\Models\MedicinaTrat;

use Illuminate\Database\Query\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class EnfermeriaController extends Controller
{

    public function listarJor(Request $request) {
        $query = MedicinaTrat::with('paciente', 'consulta', 'protocolo', 'medico', 'medicina');

        if ($request->filled('busqueda')) {
            $termino = $request->input('busqueda');
            $query->where(function($q) use ($termino) {
                $q->WhereHas('paciente', function ($pQ) use ($termino) {
                    $pQ->where('hc', 'ILIKE', "%{$termino}%");
                    $pQ->orwhereHas('persona', function ($peQ) use ($termino) {
                        $peQ->where('nombres', 'ILIKE', "%{$termino}%")
                           ->orWhere('apellidos', 'ILIKE', "%{$termino}%")
                           ->orWhereRaw("CONCAT(nombres, ' ', apellidos) ILIKE ?", ["%{$termino}%"]);
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
}
