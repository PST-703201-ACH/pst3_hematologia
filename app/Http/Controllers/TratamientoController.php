<?php

namespace App\Http\Controllers;

use App\Http\Requests\Medico\StoreProtocoloSesionRequest;
use App\Http\Requests\Medico\StoreProtocoloTratamientoRequest;
use App\Models\Auditoria;
use App\Models\Paciente;
use App\Models\Protocolo;
use App\Models\ProtocoloCiclo;
use App\Models\ProtocoloSesion;
use App\Models\ProtocoloTratamiento;
use App\Models\Sala;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class TratamientoController extends Controller
{
    public function create()
    {
        return view('medico.tratamientos.programar', [
            'pacientes' => Paciente::query()->with('persona')->orderBy('hc')->get(),
            'consultas' => DB::table('logistica.consulta as consulta')
                ->join('logistica.paciente as paciente', 'paciente.paciente_id', '=', 'consulta.paciente_id')
                ->join('persona', 'persona.persona_id', '=', 'paciente.persona_id')
                ->orderByDesc('consulta.fecha_hora')
                ->limit(500)
                ->select(
                    'consulta.consulta_id',
                    'consulta.paciente_id',
                    'consulta.fecha_hora',
                    'paciente.hc',
                    'persona.nombres',
                    'persona.apellidos'
                )
                ->get(),
            'protocolos' => Protocolo::query()->orderBy('nombre')->get(['protocolo_id', 'nombre']),
            'medicinasPro' => DB::table('logistica.medicina_pro as detalle')
                ->join('logistica.protocolo as protocolo', 'protocolo.protocolo_id', '=', 'detalle.id_protocolo')
                ->join('logistica.fase as fase', 'fase.fase_id', '=', 'detalle.id_fase')
                ->join('logistica.medicina as medicina', 'medicina.medicina_id', '=', 'detalle.id_medicina')
                ->orderBy('protocolo.nombre')
                ->orderBy('fase.numero')
                ->select(
                    'detalle.id as medicina_pro_id',
                    'detalle.id_protocolo as protocolo_id',
                    'protocolo.nombre as protocolo',
                    'fase.numero as fase',
                    'medicina.descripcion as medicamento'
                )
                ->get(),
            'salas' => Sala::query()->where('activa', true)->orderBy('nombre')->get(),
            'tratamientos' => ProtocoloTratamiento::query()
                ->with(['paciente.persona', 'protocolo'])
                ->where('estado', 'Activo')
                ->orderByDesc('fecha_inicio')
                ->limit(100)
                ->get(),
        ]);
    }

    public function store(StoreProtocoloTratamientoRequest $request)
    {
        $data = $request->validated();
        $consulta = DB::table('logistica.consulta')
            ->where('consulta_id', $data['consulta_id'])
            ->first();

        if (! $consulta || (int) $consulta->paciente_id !== (int) $data['paciente_id']) {
            throw ValidationException::withMessages([
                'consulta_id' => 'La consulta seleccionada no pertenece al paciente.',
            ]);
        }

        DB::transaction(function () use ($data, $request): void {
            $tratamiento = ProtocoloTratamiento::query()->create([
                ...$data,
                'usuario_medico_id' => $request->user()->usuario_id,
                'estado' => 'Activo',
            ]);

            Auditoria::query()->create([
                'descripcion' => "Asignación de protocolo al paciente {$tratamiento->paciente_id}",
                'modulo' => 'Tratamientos',
                'id_usuario' => $request->user()->usuario_id,
                'fecha_hora' => now(),
                'accion' => 'CREATE',
            ]);
        });

        return redirect()->route('medico.index', ['vista' => 'tratamientos'])
            ->with('success', 'Protocolo asignado al paciente.');
    }

    public function storeSesion(StoreProtocoloSesionRequest $request)
    {
        $data = $request->validated();
        $tratamiento = ProtocoloTratamiento::query()->findOrFail($data['protocolo_tratamiento_id']);

        if ($tratamiento->estado !== 'Activo') {
            throw ValidationException::withMessages([
                'protocolo_tratamiento_id' => 'No se pueden programar sesiones para un tratamiento inactivo.',
            ]);
        }

        $medicinaProPertenece = DB::table('logistica.medicina_pro')
            ->where('id', $data['medicina_pro_id'])
            ->where('id_protocolo', $tratamiento->protocolo_id)
            ->exists();

        if (! $medicinaProPertenece) {
            throw ValidationException::withMessages([
                'medicina_pro_id' => 'El medicamento no pertenece al protocolo asignado.',
            ]);
        }

        DB::transaction(function () use ($data, $request, $tratamiento): void {
            $nombreSala = Str::squish($data['sala_nombre']);
            $sala = Sala::query()->whereRaw('lower(nombre) = lower(?)', [$nombreSala])->first();
            $sala ??= Sala::query()->create(['nombre' => $nombreSala, 'activa' => true]);

            $ciclo = ProtocoloCiclo::query()->firstOrCreate(
                [
                    'protocolo_tratamiento_id' => $tratamiento->protocolo_tratamiento_id,
                    'numero_ciclo' => $data['numero_ciclo'],
                ],
                [
                    'fecha_inicio' => substr($data['fecha_hora'], 0, 10),
                    'estado' => 'En curso',
                ]
            );

            $sesion = ProtocoloSesion::query()->create([
                'ciclo_id' => $ciclo->ciclo_id,
                'medicina_pro_id' => $data['medicina_pro_id'],
                'sala_id' => $sala->sala_id,
                'fecha_hora' => $data['fecha_hora'],
                'tipo_sesion' => $data['tipo_sesion'],
                'dosis' => $data['dosis'],
                'via_administracion' => $data['via_administracion'],
                'status' => 'Programada',
                'identidad_confirmada' => false,
            ]);

            Auditoria::query()->create([
                'descripcion' => "Programación de sesión {$sesion->sesion_id} para tratamiento {$tratamiento->protocolo_tratamiento_id}",
                'modulo' => 'Tratamientos',
                'id_usuario' => $request->user()->usuario_id,
                'fecha_hora' => now(),
                'accion' => 'CREATE',
            ]);
        });

        return redirect()->route('medico.index', ['vista' => 'tratamientos'])
            ->with('success', 'Sesión programada para Enfermería.');
    }
}
