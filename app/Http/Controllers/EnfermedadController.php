<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Enfermedad;
use Exception;

class EnfermedadController extends Controller
{
    /**
     * MÉTODO POO: Retorna la colección de todas las enfermedades activas (CIE-10).
     */
    public function index()
    {
        $enfermedades = Enfermedad::where('activa', true)->get();

        return response()->json([
            'success' => true,
            'data'    => $enfermedades
        ], 200);
    }

    /**
     * MÉTODO POO: Registra una nueva entidad patológica en el sistema.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'codigo_cie10' => 'required|string|max:10|unique:enfermedad,codigo_cie10',
            'nombre'       => 'required|string|max:100',
            'descripcion'  => 'nullable|string'
        ]);

        try {
            // Instanciamos y guardamos el objeto Enfermedad
            $enfermedad = Enfermedad::create($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'Patología CIE-10 registrada exitosamente.',
                'data'    => $enfermedad
            ], 201);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al registrar la patología.',
                'error'   => $e->getMessage()
            ], 500);
        }
    }
}