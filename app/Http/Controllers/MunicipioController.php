<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Municipio;

class MunicipioController extends Controller
{
    public function getMunicipios(Request $request)
    {
        $query = Municipio::query();
        if ($request->has('estado_id')) {
            $query->where('estado_id', $request->estado_id);
        }
        $municipios = $query->get(['municipio_id', 'nombre']);
        return response()->json($municipios);
    }
}
