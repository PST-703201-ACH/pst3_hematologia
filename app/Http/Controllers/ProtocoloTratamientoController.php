<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProtocoloTratamiento;
use App\Models\ProtocoloCiclo;
use App\Models\ProtocoloSesion; // <-- ¡Faltaba importar este modelo!
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ProtocoloTratamientoController extends Controller
{
    public function store(Request $request)
    {
        // 1. Validar los datos de entrada
        $request->validate([
            'paciente_id' => 'required|exists:paciente,paciente_id',
            'fecha_inicio' => 'required|date',
            // Agrega aquí otras validaciones necesarias
        ]);

        // Iniciamos la transacción para proteger la base de datos
        DB::beginTransaction();

        try {
            // 2. Crear el registro principal del Protocolo
            $protocolo = ProtocoloTratamiento::create([
                'paciente_id' => $request->paciente_id,
                'consulta_id' => $request->consulta_id, // Si viene de una consulta
                'nombre' => 'Protocolo Nacional',
                'indicaciones' => $request->indicaciones,
                'fecha_inicio' => $request->fecha_inicio,
                'estado' => 'Activo',
                'usuario_medico_id' => auth()->user()->usuario_id, // El médico logueado
            ]);

            // 3. Preparar las fechas con Carbon
            $fechaInicio = Carbon::parse($request->fecha_inicio);

            // -- FASE 1: INDUCCIÓN (1 mes) --
            $protocolo->ciclos()->create([
                'numero_ciclo' => 1,
                'fecha_inicio' => $fechaInicio->copy(), // Usa copy() para no alterar la variable original
                'estado' => 'Pendiente'
            ]);
            $finInduccion = $fechaInicio->copy()->addMonth();

            // -- FASE 2: CONSOLIDACIÓN 1 (24 horas) --
            $protocolo->ciclos()->create([
                'numero_ciclo' => 2,
                'fecha_inicio' => $finInduccion->copy(),
                'estado' => 'Pendiente'
            ]);
            $finConsolidacion1 = $finInduccion->copy()->addDay(); // 24 horas = 1 día

            // -- FASE 3: CONSOLIDACIÓN 2 (35 semanas) --
            $protocolo->ciclos()->create([
                'numero_ciclo' => 3,
                'fecha_inicio' => $finConsolidacion1->copy(),
                'estado' => 'Pendiente'
            ]);
            $finConsolidacion2 = $finConsolidacion1->copy()->addWeeks(35);

            // -- FASE 4: MANTENIMIENTO 1 --
            $protocolo->ciclos()->create([
                'numero_ciclo' => 4,
                'fecha_inicio' => $finConsolidacion2->copy(),
                'estado' => 'Pendiente'
            ]);
            
            // -- FASE 5: MANTENIMIENTO 2 --
            $protocolo->ciclos()->create([
                'numero_ciclo' => 5,
                'fecha_inicio' => $finConsolidacion2->copy(), // Cambiar cuando tengas el tiempo exacto de la fase 4
                'estado' => 'Pendiente'
            ]);

            // Si todo salió bien, guardamos los cambios definitivamente
            DB::commit();

            return redirect()->route('protocolos.index')->with('success', 'Protocolo Nacional asignado y fases generadas automáticamente.');

        } catch (\Exception $e) {
            // Si hay un error, revertimos absolutamente todo para no dejar registros huérfanos
            DB::rollBack();
            
            // Retornamos el error para depurar
            return back()->withErrors(['error' => 'Hubo un problema al generar el protocolo: ' . $e->getMessage()]);
        }
    } // <-- ¡Esta es la llave que faltaba para cerrar la función store()!

    public function reprogramarSemana(Request $request, $sesion_id)
    {
        // 1. Buscamos la sesión a la que el paciente no asistió
        $sesionPerdida = ProtocoloSesion::findOrFail($sesion_id);
        
        // 2. Marcamos esa sesión original como "No Asistió"
        $sesionPerdida->update(['status' => 'No Asistió']);

        // 3. Desplazamos TODAS las sesiones futuras de ese mismo ciclo que sigan "Programadas".
        ProtocoloSesion::where('ciclo_id', $sesionPerdida->ciclo_id)
                       ->where('fecha_hora', '>', $sesionPerdida->fecha_hora)
                       ->where('status', 'Programada')
                       ->update([
                           // Sumamos 7 días (1 semana) a la fecha original usando PostgreSQL
                           'fecha_hora' => DB::raw("fecha_hora + INTERVAL '1 week'")
                       ]);

        return redirect()->back()->with('success', 'Semana reprogramada. Todas las sesiones futuras se han desplazado 7 días.');
    }
}