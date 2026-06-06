<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Examen;
use Exception;

class ExamenController extends Controller
{
    /**
     * MÉTODO POO: Devuelve la colección de todos los objetos Examen activos.
     */
    public function index()
    {
        // Recuperamos todos los exámenes marcados como activos
        $examenes = Examen::where('activo', true)->get();

        return response()->json([
            'success' => true,
            'data'    => $examenes
        ], 200);
    }

    /**
     * MÉTODO POO: Registra un nuevo examen en el catálogo clínico del laboratorio.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'codigo_examen'           => 'required|string|max:10|unique:examen,codigo_examen',
            'nombre'                  => 'required|string|max:100',
            'unidad_medida'           => 'required|string|max:20',
            'valor_minimo_referencia' => 'required|numeric',
            'valor_maximo_referencia' => 'required|numeric',
            'indicaciones'            => 'nullable|string'
        ]);

        try {
            // Instanciamos y guardamos el nuevo objeto Examen
            $examen = Examen::create($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'Examen clínico añadido correctamente al catálogo.',
                'data'    => $examen
            ], 201);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al registrar el examen.',
                'error'   => $e->getMessage()
            ], 500);
        }
    }
}