<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Parroquia;

class ParroquiaController extends Controller
{
    public function getParroquias(){
        $parroquias = Parroquia::all(['parroquia_id', 'nombre']); 
        return response()->json($parroquias);
    }

   
}