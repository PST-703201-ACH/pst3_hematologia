<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Municipio;

class MunicipioController extends Controller
{
   public function getMunicipios(){
      $municipios = Municipio::all(['municipio_id', 'nombre']); 
      return response()->json($municipios);
   }
}
