<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Persona;
use App\Models\Usuario;
use App\Models\Rol;


class GerenteController extends Controller
{
    public function dashboards(){
        //Usuarios
        $totalUsuarios = Usuario::count();
        $gerentes = Usuario::where('id_rol', '1')->count();
        $administrativos = Usuario::where('id_rol', '2')->count();
        $medicos = Usuario::where('id_rol', '3')->count();
        $enfermeros = Usuario::where('id_rol', '4')->count();
        
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
}