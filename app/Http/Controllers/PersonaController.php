<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Persona;
use Exception;

class PersonaController extends Controller
{
    /**
     * MÉTODO POO: Guarda una nueva instancia (objeto) de Persona en el sistema.
     */
    public function store(Request $request)
    {
        // 1. Validamos los datos de entrada que formarán el objeto
        $validatedData = $request->validate([
            'cedula'            => 'required|string|max:20|unique:personas,cedula',
            'nombres'           => 'required|string|max:100',
            'apellidos'         => 'required|string|max:100',
            'fecha_nacimiento'  => 'required|date',
            'sexo'              => 'required|in:M,F',
            'telefono'          => 'nullable|string|max:20',
            'correo'            => 'nullable|email|max:100|unique:personas,correo',
            'direccion_detalle' => 'nullable|string',
            'parroquia_id'      => 'nullable|exists:parroquias,id'
        ]);

        try {
            // 2. Aplicamos POO: Creamos y guardamos el objeto directamente en la base de datos
            $persona = Persona::create($validatedData);

            // 3. Retornamos la respuesta con el objeto recién creado
            return response()->json([
                'success' => true,
                'message' => 'Paciente registrado exitosamente con el enfoque POO.',
                'data'    => $persona
            ], 201);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al instanciar o guardar el objeto Paciente.',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    /**
     * MÉTODO POO: Recupera un objeto Persona específico por su Cédula.
     */
    public function showByCedula($cedula)
    {
        // Buscamos el objeto usando el modelo de datos
        $persona = Persona::where('cedula', $cedula)->with('parroquia.municipio.estado')->first();

        if (!$persona) {
            return response()->json([
                'success' => false,
                'message' => 'No se encontró ningún objeto paciente con esa cédula.'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data'    => $persona
        ], 200);
    }
}