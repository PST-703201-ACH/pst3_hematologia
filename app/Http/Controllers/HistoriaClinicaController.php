<?php

namespace App\Http\Controllers;

use App\Models\Paciente;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Entrega la historia clínica de solo lectura usando únicamente datos persistidos.
 */
class HistoriaClinicaController extends Controller
{
    private const SEARCH_LIMIT = 50;

    public function pacientes(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'q' => ['nullable', 'string', 'max:100'],
        ]);
        $term = trim($validated['q'] ?? '');

        $query = Paciente::query()
            ->with(['persona:persona_id,nombres,apellidos'])
            ->select(['paciente_id', 'persona_id', 'hc', 'status']);

        if ($term !== '') {
            $pattern = '%' . mb_strtolower($term) . '%';
            $query->where(function ($patients) use ($pattern): void {
                $patients
                    ->whereRaw('CAST(hc AS VARCHAR) LIKE ?', [$pattern])
                    ->orWhereHas('persona', function ($personas) use ($pattern): void {
                        $personas
                            ->whereRaw('LOWER(nombres) LIKE ?', [$pattern])
                            ->orWhereRaw('LOWER(apellidos) LIKE ?', [$pattern]);
                    });
            });
        }

        $total = (clone $query)->count();
        $patients = $query->orderBy('hc')->limit(self::SEARCH_LIMIT)->get();

        return response()->json([
            'data' => $patients->map(fn (Paciente $patient): array => [
                'paciente_id' => $patient->paciente_id,
                'hc' => $patient->hc,
                'nombre' => trim(($patient->persona?->nombres ?? '') . ' ' . ($patient->persona?->apellidos ?? '')),
                'status' => $patient->status,
            ])->values(),
            'total' => $total,
            'limit' => self::SEARCH_LIMIT,
        ]);
    }

    public function detalle(int $pacienteId): JsonResponse
    {
        $patient = Paciente::query()
            ->with([
                'persona.estado',
                'persona.municipio',
                'persona.parroquia',
            ])
            ->findOrFail($pacienteId);

        // Limitar las consultas a las tablas principales evita depender de tablas
        // opcionales ausentes en algunas instalaciones y de esquemas desechables.
        $consultations = $patient->consultas()->with('enfermedad')->get();

        return response()->json([
            'paciente' => [
                'paciente_id' => $patient->paciente_id,
                'hc' => $patient->hc,
                'status' => $patient->status,
                'persona' => [
                    'nombres' => $patient->persona?->nombres,
                    'apellidos' => $patient->persona?->apellidos,
                    'fecha_nacimiento' => $patient->persona?->fecha_nacimiento,
                    'sexo' => $patient->persona?->sexo,
                    'cedula' => $patient->persona?->cedula,
                    'telefono' => $patient->persona?->telefono,
                    'email' => $patient->persona?->email,
                    'direccion_exacta' => $patient->persona?->direccion_exacta,
                    'estado' => $patient->persona?->estado?->nombre,
                    'municipio' => $patient->persona?->municipio?->nombre,
                    'parroquia' => $patient->persona?->parroquia?->nombre,
                ],
            ],
            'consultas' => $consultations->map(fn ($consultation): array => [
                'consulta_id' => $consultation->consulta_id,
                'tipo' => $consultation->tipo,
                'fecha_hora' => $consultation->fecha_hora,
                'enfermedad' => $consultation->enfermedad?->descripcion,
                'status' => $consultation->status,
                'peso' => $consultation->peso,
                'talla' => $consultation->talla,
                'sc' => $consultation->sc,
                'fc' => $consultation->fc,
                'fr' => $consultation->fr,
                'ante_personal' => $consultation->ante_personal,
                'ante_familiar' => $consultation->ante_familiar,
                'sintomas' => $consultation->sintomas,
                'subjetivo' => $consultation->subjetivo,
                'plan_trabajo' => $consultation->plan_trabajo,
                'proxima_cita' => $consultation->proxima_cita,
            ])->values(),
        ]);
    }
}
