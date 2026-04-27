<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rol;

class RolController extends Controller
{
   public function getRoles(){
        $roles = Rol::all(['rol_id', 'nombre']); 
        return response()->json($roles);
   }
}
