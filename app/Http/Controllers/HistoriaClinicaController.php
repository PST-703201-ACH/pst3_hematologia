<?php

namespace App\Http\Controllers;

use App\Models\Paciente;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

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
                            ->orWhereRaw('LOWER(apellidos) LIKE ?', [$pattern])
                            ->orWhereRaw("LOWER(nombres || ' ' || apellidos) LIKE ?", [$pattern]);
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

    public function detalle(int $pacienteId): Response
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
        $protocolTablesAvailable = Schema::hasTable('logistica.medicina_trat')
            && Schema::hasTable('logistica.protocolo');
        $protocolsByConsultation = collect();

        // Los protocolos se vinculan a la consulta; una fila por medicina puede repetirlos.
        if ($protocolTablesAvailable && $consultations->isNotEmpty()) {
            $protocolsByConsultation = DB::table('logistica.medicina_trat as asignacion')
                ->join('logistica.protocolo as protocolo', 'protocolo.protocolo_id', '=', 'asignacion.protocolo_id')
                ->where('asignacion.paciente_id', $patient->paciente_id)
                ->whereIn('asignacion.consulta_id', $consultations->pluck('consulta_id'))
                ->select('asignacion.consulta_id', 'protocolo.protocolo_id', 'protocolo.nombre')
                ->distinct()
                ->orderBy('protocolo.nombre')
                ->get()
                ->groupBy('consulta_id')
                ->map(fn ($protocols) => $protocols->pluck('nombre')->values()->all());
        }

        return response()->view('medico.pacientes.detalle_historia', [
            'patient' => $patient,
            'consultations' => $consultations,
            'protocolsByConsultation' => $protocolsByConsultation,
        ]);
    }
}
