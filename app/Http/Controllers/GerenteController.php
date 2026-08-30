<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Persona;
use App\Models\User;
use App\Models\Rol;
use App\Models\Enfermedad;

class GerenteController extends Controller
{
    public function dashboards()
    {
        //Usuarios
        $totalUsuarios = User::count();
        $gerentes = User::where('id_rol', '1')->count();
        $administrativos = User::where('id_rol', '2')->count();
        $medicos = User::where('id_rol', '3')->count();
        $enfermeros = User::where('id_rol', '4')->count();
        
        //Personas
        $totalPersonas = Persona::count();
        $hombres = Persona::where('sexo', 'Masculino')->count(); 
        $mujeres = Persona::where('sexo', 'Femenino')->count(); 


        return response()->json([
            //Usuarios
            'usuarios' => $totalUsuarios,
            'gerentes' => $gerentes,
            'administrativos' => $administrativos,
            'medicos' => $medicos,
            'enfermeros' => $enfermeros,

            //Personas
            'personas' => $totalPersonas,

            'hombres' => $hombres,
            'mujeres' => $mujeres
        ]);
    }

    public function listarEnf(Request $request)
    {
        $query = Enfermedad::query();

        if ($request->filled('busqueda')) {
            $termino = $request->input('busqueda');
            
            $query->where(function($q) use ($termino) {
                $q->where('descripcion', 'ILIKE', "%{$termino}%");
            });     
        }

        if ($request->filled('tip')) {
            $tipo = $request->input('tip');
            
            $query->where('tipo', (int)$tipo); 
        }

        return response()->json($query->get());
    }

    public function registrarEnf(Request $request)
    {

        $errores = [];

        if (empty($request->nombreEnfReg)) {
            $errores['nombreEnfReg'] = "Ingrese el nombre de la enfermedad";
        }
        
        if (empty($request->tipEnfReg)) {
            $errores['tipEnfReg'] = "";
        }

        if (!empty($errores)) {
            return response()->json([
                "status" => "errores",
                "errores" => $errores
            ]);
            exit;
        } else {

            try {

                $enfermedad = new Enfermedad;

                $enfermedad->descripcion = $request->nombreEnfReg;
                $enfermedad->tipo = $request->tipEnfReg;

                if ($enfermedad->save()) {
                    return response()->json(['status' => 'exito', 'mensaje' => 'Se ha registrado la enfermedad exitosamente']);    
                }               
            } catch (\Exception $e) {
                return response()->json([
                    'status' => 'error',
                    'mensaje' => 'Error de servidor o base de datos: '. $e->getMessage()
                ]);
            }
        }
    }

    public function precargarEnf($id){
        $enfermedad = Enfermedad::query()->where('enfermedad_id', $id)->first();
        return response()->json($enfermedad);
    }

    public function actualizarEnf(Request $request)
    {
        $errores = [];

        if (empty($request->nombreEnfUp)) {
            $errores['nombreEnfUp'] = "Ingrese el nombre de la enfermedad";
        }
        
        if (empty($request->tipEnfUp)) {
            $errores['tipEnfUp'] = "";
        }

        if (!empty($errores)) {
            return response()->json([
                "status" => "errores",
                "errores" => $errores
            ]);
            exit;
        } else {

            try {

                $enfermedad = Enfermedad::findOrFail($request->enfermedadUp);

                $enfermedad->update([
                    'descripcion' => $request->nombreEnfUp,
                    'tipo' => $request->tipEnfUp
                ]);

                return response()->json(['status' => 'exito', 'mensaje' => 'Se ha actualizado la enfermedad exitosamente']);                   
            } catch (\Exception $e) {
                return response()->json([
                    'status' => 'error',
                    'mensaje' => 'Error de servidor o base de datos: '. $e->getMessage()
                ]);
            }
        }
    }
}