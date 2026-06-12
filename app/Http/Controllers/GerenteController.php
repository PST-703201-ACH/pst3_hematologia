<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Persona;
use App\Models\User;
use App\Models\Rol;


class GerenteController extends Controller
{
    public function dashboards(){
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
}