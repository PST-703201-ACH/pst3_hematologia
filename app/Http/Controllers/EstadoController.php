<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Estado;

class EstadoController extends Controller
{
    public function getEstados(){
    $estados = Estado::all(['estado_id', 'nombre']); 

    return response()->json($estados);
    }
}
