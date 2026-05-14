<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Persona;
use App\Models\User;
use App\Models\Rol;

class UsuarioController extends Controller
{
public function listar(Request $request){
    $query = User::with('persona', 'rol');

    if ($request->filled('busqueda')) {
        $termino = $request->input('busqueda');
        
        $query->where(function($q) use ($termino) {
            $q->where('username', 'ILIKE', "%{$termino}%")
              ->orWhereHas('persona', function($subQuery) use ($termino) {
                  $subQuery->where('nombres', 'ILIKE', "%{$termino}%")
                           ->orWhere('apellidos', 'ILIKE', "%{$termino}%")
                           ->orWhereRaw("CONCAT(nombres, ' ', apellidos) ILIKE ?", ["%{$termino}%"])
                           ->orWhere('telefono', 'ILIKE', "%{$termino}%")
                           ->orWhere('cedula', 'ILIKE', "%{$termino}%");
              });
        });     
    }

    if ($request->filled('status')) {
        $status = $request->input('status');
        
        $query->where('status', (int)$status); 
    }

    if ($request->filled('rol')) {
        $rol = $request->input('rol');
        
        $query->where('id_rol', (int)$rol); 
    }

    return response()->json($query->get());
}

        

    public function ver($id){
        $usuario = User::with('persona.estado', 'persona.municipio', 'persona.parroquia', 'rol')->where('persona_id', $id)->first();
        return response()->json($usuario);
    }

    public function precargar($id){
        $usuario = User::with('persona.estado', 'persona.municipio', 'persona.parroquia', 'rol')->where('persona_id', $id)->first();
        return response()->json($usuario);
    }

    public function nuevo_status($id){
        $usuario = User::where('persona_id', $id)->first();

        if ($usuario) {
            try{
                switch ($usuario->status) {
                    case '1':
                        $usuario->update([
                            'status' => '0'
                        ]);
                        return response()->json(['status' => 'exito', 'contenido' => '<p class="text-danger">Inactivo</p>']);
                    break;

                    case '0':
                        $usuario->update([
                            'status' => '1'
                        ]);
                        return response()->json(['status' => 'exito', 'contenido' => '<p class="text-success">Activo</p>']);
                    break;
                }
            } catch (\Exception $e) {
                return response()->json([
                    'status' => 'error'
                ]);
            }
        }
    }
    
}
