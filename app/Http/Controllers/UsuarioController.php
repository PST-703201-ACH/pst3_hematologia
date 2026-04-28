<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Persona;
use App\Models\Usuario;
use App\Models\Rol;

class UsuarioController extends Controller
{
    public function listar(){
        $usuarios = Usuario::with('persona', 'rol')->get();

        return response()->json($usuarios);
    }
}
