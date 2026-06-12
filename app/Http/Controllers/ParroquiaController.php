<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Parroquia;

class ParroquiaController extends Controller
{
    public function getParroquias(Request $request)
    {
        $query = Parroquia::query();
        if ($request->has('municipio_id')) {
            $query->where('municipio_id', $request->municipio_id);
        }
        $parroquias = $query->get(['parroquia_id', 'nombre']); 
        return response()->json($parroquias);
    }

   
}