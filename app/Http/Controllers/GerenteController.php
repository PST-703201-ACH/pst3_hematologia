<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Persona;
use App\Models\User;
use App\Models\Rol;
use App\Models\Enfermedad;
use App\Models\Medicina;
use App\Models\Fase;
use App\Models\MedicinaProt;
use App\Models\Protocolo;

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

    public function registrarProt(Request $request)
    {

        $errores = [];

        if (empty($request->nombreProtReg)) {
            $errores['nombreProtReg'] = "Ingrese el nombre del protocolo";
        }

        if (!empty($errores)) {
            return response()->json([
                "status" => "errores",
                "errores" => $errores
            ]);
            exit;
        } else {

            try {
                DB::beginTransaction();

                $nombre = $request->nombreProtReg;

                $protocolo = Protocolo::create([
                    'nombre' => $nombre
                ]);

                $datos = $request->all();

                foreach ($datos as $clave => $valoresSelect) {
                    if (str_starts_with($clave, 'med_fase-')) {
                        
                        // Extracción de número de fase
                        $numeroFase = str_replace('med_fase-', '', $clave);
                        
                        // Registro de la fase
                        $fase = Fase::create([
                            'numero' => $numeroFase
                        ]);
                        $claveVia = 'via_fase-' . $numeroFase;
                        
                        $viasFase = $datos[$claveVia] ?? [];

                        foreach ($valoresSelect as $indice => $medicina) {
                            
                            $via = $viasFase[$indice] ?? null; 
                            
                            MedicinaProt::create([
                                'id_protocolo' => $protocolo->protocolo_id,
                                'id_fase'      => $fase->fase_id,
                                'id_medicina'  => $medicina,
                                'via_admin'       => $via
                            ]);
                        }
                    }
                }

                DB::commit();

                return response()->json([
                    'status' => 'exito', 
                    'mensaje' => 'Se ha registrado el protocolo exitosamente'
                ]);

            } catch (\Exception $e) {
                DB::rollBack();

                return response()->json([
                    'status' => 'error',
                    'mensaje' => 'Error de servidor o base de datos: '. $e->getMessage()
                ]);
            }

        }
    }

    public function listarProt(Request $request)
    {
        $query = Protocolo::query();

        if ($request->filled('busqueda')) {
            $termino = $request->input('busqueda');
            
            $query->where(function($q) use ($termino) {
                $q->where('descripcion', 'ILIKE', "%{$termino}%");
            });     
        }

        return response()->json($query->get());
    }

    public function verFases($id){
        $fases = MedicinaProt::with('protocolo', 'medicina', 'fase')->where('id_protocolo', $id)->get();
        return response()->json($fases);
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

    public function listarMed(Request $request)
    {
        $query = Medicina::query();

        if ($request->filled('busqueda')) {
            $termino = $request->input('busqueda');
            
            $query->where(function($q) use ($termino) {
                $q->where('descripcion', 'ILIKE', "%{$termino}%");
            });     
        }

        return response()->json($query->get());
    }

    public function listarMedDis()
    {
        $query = Medicina::all('medicina_id', 'descripcion');

        return response()->json($query);
    }


    public function registrarMed(Request $request)
    {

        $errores = [];

        if (empty($request->nombreMedReg)) {
            $errores['nombreMedReg'] = "Ingrese el nombre de la medicina";
        }
        

        if (!empty($errores)) {
            return response()->json([
                "status" => "errores",
                "errores" => $errores
            ]);
            exit;
        } else {

            try {

                $medicina = new Medicina;

                $medicina->descripcion = $request->nombreMedReg;


                if ($medicina->save()) {
                    return response()->json(['status' => 'exito', 'mensaje' => 'Se ha registrado la medicina exitosamente']);    
                }               
            } catch (\Exception $e) {
                return response()->json([
                    'status' => 'error',
                    'mensaje' => 'Error de servidor o base de datos: '. $e->getMessage()
                ]);
            }
        }
    }

    public function precargarMed($id){
        $medicina = Medicina::query()->where('medicina_id', $id)->first();
        return response()->json($medicina);
    }

    public function actualizarMed(Request $request)
    {
        $errores = [];

        if (empty($request->nombreMedUp)) {
            $errores['nombreMedUp'] = "Ingrese el nombre de la medicina";
        }

        if (!empty($errores)) {
            return response()->json([
                "status" => "errores",
                "errores" => $errores
            ]);
            exit;
        } else {

            try {

                $medicina = Medicina::findOrFail($request->medicinaUp);

                $medicina->update([
                    'descripcion' => $request->nombreMedUp
                ]);

                return response()->json(['status' => 'exito', 'mensaje' => 'Se ha actualizado la medicina exitosamente']);                   
            } catch (\Exception $e) {
                return response()->json([
                    'status' => 'error',
                    'mensaje' => 'Error de servidor o base de datos: '. $e->getMessage()
                ]);
            }
        }
    }


}