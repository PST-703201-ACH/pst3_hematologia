<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Calcula métricas del panel Médico desde las entidades clínicas principales.
 */
class MedicoDashboardMetrics
{
    public function build(): array
    {
        $treatmentTablesAvailable = collect([
            'logistica.medicina_trat',
            'logistica.tratamiento',
            'logistica.protocolo',
            'logistica.medicina',
        ])->every(fn (string $table): bool => Schema::hasTable($table));

        $protocolUses = $treatmentTablesAvailable
            ? DB::table('logistica.medicina_trat as asignacion')
                ->join('logistica.tratamiento as tratamiento', 'tratamiento.tratamiento_id', '=', 'asignacion.tratamiento_id')
                ->join('logistica.protocolo as protocolo', 'protocolo.protocolo_id', '=', 'asignacion.protocolo_id')
                ->join('logistica.medicina as medicina', 'medicina.medicina_id', '=', 'asignacion.medicina_id')
                ->where('tratamiento.status', 1)
                ->select('protocolo.protocolo_id', 'protocolo.nombre as protocolo', 'medicina.medicina_id', 'medicina.descripcion as medicina')
                ->selectRaw('COUNT(DISTINCT asignacion.tratamiento_id) as usos')
                ->groupBy('protocolo.protocolo_id', 'protocolo.nombre', 'medicina.medicina_id', 'medicina.descripcion')
                ->orderBy('protocolo.nombre')
                ->orderBy('medicina.descripcion')
                ->get()
            : collect();

        return [
            'historias_registradas' => DB::table('logistica.paciente')->count(),
            'protocolos_activos' => $treatmentTablesAvailable
                ? (int) DB::table('logistica.medicina_trat as asignacion')
                    ->join('logistica.tratamiento as tratamiento', 'tratamiento.tratamiento_id', '=', 'asignacion.tratamiento_id')
                    ->where('tratamiento.status', 1)
                    ->distinct()
                    ->count('asignacion.tratamiento_id')
                : null,
            'interconsultas_realizadas' => DB::table('logistica.consulta')
                ->where('tipo', 2)
                ->where('status', 1)
                ->count(),
            'pacientes_por_enfermedad' => DB::table('logistica.consulta as consulta')
                ->join('logistica.enfermedad as enfermedad', 'enfermedad.enfermedad_id', '=', 'consulta.enfermedad_id')
                ->where('consulta.status', 1)
                ->whereNotNull('consulta.paciente_id')
                ->select('enfermedad.descripcion as etiqueta')
                ->selectRaw('COUNT(DISTINCT consulta.paciente_id) as total')
                ->groupBy('enfermedad.enfermedad_id', 'enfermedad.descripcion')
                ->orderBy('enfermedad.descripcion')
                ->get()
                ->map(fn ($row): array => ['etiqueta' => $row->etiqueta, 'total' => (int) $row->total])
                ->values()
                ->all(),
            'pacientes_por_sexo' => DB::table('logistica.paciente as paciente')
                ->join('persona', 'persona.persona_id', '=', 'paciente.persona_id')
                ->selectRaw("COALESCE(NULLIF(TRIM(persona.sexo), ''), 'No especificado') as etiqueta")
                ->selectRaw('COUNT(DISTINCT paciente.paciente_id) as total')
                ->groupByRaw("COALESCE(NULLIF(TRIM(persona.sexo), ''), 'No especificado')")
                ->orderBy('etiqueta')
                ->get()
                ->map(fn ($row): array => ['etiqueta' => $row->etiqueta, 'total' => (int) $row->total])
                ->values()
                ->all(),
            'usos_por_protocolo_medicina' => $protocolUses
                ->map(fn ($row): array => [
                    'etiqueta' => $row->protocolo,
                    'medicina' => $row->medicina,
                    'total' => (int) $row->usos,
                ])
                ->values()
                ->all(),
            'protocolos_disponibles' => $treatmentTablesAvailable,
        ];
    }
}
