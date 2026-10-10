<?php

namespace App\Http\Controllers;

use App\Http\Requests\Enfermeria\AplicarSesionRequest;
use App\Http\Requests\Enfermeria\AplicarTransfusionRequest;
use App\Http\Requests\Enfermeria\PanelEnfermeriaRequest;
use App\Http\Requests\Enfermeria\StoreObservacionRequest;
use App\Http\Requests\Enfermeria\StoreReaccionAdversaRequest;
use App\Models\Auditoria;
use App\Models\ObservacionEnfermeria;
use App\Models\ProtocoloSesion;
use App\Models\ReaccionAdversa;
use App\Models\Sala;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class EnfermeriaController extends Controller
{
    public function index(PanelEnfermeriaRequest $request)
    {
        $filters = $request->validated();
        $date = Carbon::parse($filters['fecha'] ?? today()->toDateString());

        return view('enfermeria.index', [
            'salas' => Sala::query()->where('activa', true)->orderBy('nombre')->get(),
            'pacientes' => DB::table('logistica.paciente as paciente')
                ->join('persona', 'persona.persona_id', '=', 'paciente.persona_id')
                ->orderBy('persona.apellidos')
                ->orderBy('persona.nombres')
                ->select('paciente.paciente_id', 'paciente.hc', 'persona.nombres', 'persona.apellidos')
                ->get(),
            'sesionesProgramadas' => $this->sesionesProgramadas($filters, $date),
            'transfusionesProgramadas' => $this->transfusionesProgramadas($filters, $date)->get(),
            'observaciones' => DB::table('logistica.observacion_enfermeria as observacion')
                ->leftJoin('logistica.sala as sala', 'sala.sala_id', '=', 'observacion.sala_id')
                ->leftJoin('logistica.paciente as paciente', 'paciente.paciente_id', '=', 'observacion.paciente_id')
                ->leftJoin('persona', 'persona.persona_id', '=', 'paciente.persona_id')
                ->orderByDesc('observacion.fecha_hora')
                ->limit(20)
                ->select(
                    'observacion.observacion_id',
                    'observacion.categoria',
                    'observacion.gravedad',
                    'observacion.fecha_hora',
                    'observacion.descripcion',
                    'sala.nombre as sala',
                    'paciente.hc',
                    'persona.nombres',
                    'persona.apellidos'
                )
                ->get(),
            'sesionesParaRegistro' => $this->sesionesActivas(),
            'transfusionesParaRegistro' => DB::table('desechable.transfusion as transfusion')
                ->join('desechable.unidad_hemocomponente as unidad', 'unidad.unidad_id', '=', 'transfusion.unidad_id')
                ->join('logistica.paciente as paciente', 'paciente.paciente_id', '=', 'transfusion.paciente_id')
                ->orderByDesc('transfusion.fecha_hora')
                ->limit(100)
                ->select(
                    'transfusion.transfusion_id',
                    'transfusion.paciente_id',
                    'transfusion.status',
                    'unidad.codigo_bolsa',
                    'paciente.hc'
                )
                ->get(),
        ]);
    }

    public function sesiones(PanelEnfermeriaRequest $request): JsonResponse
    {
        $filters = $request->validated();
        $date = Carbon::parse($filters['fecha'] ?? today()->toDateString());

        return response()->json($this->sesionesProgramadas($filters, $date));
    }

    public function aplicarSesion(AplicarSesionRequest $request, int $sesionId)
    {
        DB::transaction(function () use ($request, $sesionId): void {
            $sesion = ProtocoloSesion::query()->lockForUpdate()->findOrFail($sesionId);

            if ($sesion->status !== 'Programada') {
                throw ValidationException::withMessages([
                    'sesion' => 'Solo se pueden registrar sesiones que estén programadas.',
                ]);
            }

            $sesion->update([
                'status' => 'Realizada',
                'fecha_hora_aplicacion' => $request->validated('fecha_hora_aplicacion'),
                'usuario_enfermero_id' => $request->user()->usuario_id,
                'identidad_confirmada' => true,
                'observaciones_enfermeria' => $request->validated('observaciones_enfermeria'),
            ]);

            $this->registrarAuditoria(
                $request->user()->usuario_id,
                "Aplicación de la sesión clínica {$sesion->sesion_id}",
                'UPDATE'
            );
        });

        return redirect()->route('enfermeria.index')
            ->with('success', 'Aplicación registrada con la hora y el usuario responsable.');
    }

    public function aplicarTransfusion(AplicarTransfusionRequest $request, int $transfusionId)
    {
        DB::transaction(function () use ($request, $transfusionId): void {
            $transfusion = DB::table('desechable.transfusion')
                ->where('transfusion_id', $transfusionId)
                ->lockForUpdate()
                ->first();

            if (! $transfusion || $transfusion->status !== 'Programada') {
                throw ValidationException::withMessages([
                    'transfusion' => 'La transfusión ya no está programada o no existe.',
                ]);
            }

            $unidad = DB::table('desechable.unidad_hemocomponente')
                ->where('unidad_id', $transfusion->unidad_id)
                ->lockForUpdate()
                ->first();
            $horaAplicacion = Carbon::parse($request->validated('fecha_hora_aplicacion'));

            if (! $unidad || ! in_array($unidad->status, ['Disponible', 'Reservada'], true)) {
                throw ValidationException::withMessages([
                    'transfusion' => 'La unidad no está disponible para administración.',
                ]);
            }

            if ($unidad->fecha_vencimiento && Carbon::parse($unidad->fecha_vencimiento)->lt($horaAplicacion->copy()->startOfDay())) {
                throw ValidationException::withMessages([
                    'transfusion' => 'La unidad de hemocomponente está vencida y no puede administrarse.',
                ]);
            }

            DB::table('desechable.transfusion')
                ->where('transfusion_id', $transfusionId)
                ->update([
                    'status' => 'Realizada',
                    'fecha_hora_aplicacion' => $horaAplicacion,
                    'sala_id' => $request->validated('sala_id'),
                    'enfermera_id' => $request->user()->usuario_id,
                    'identidad_confirmada' => true,
                ]);

            DB::table('desechable.unidad_hemocomponente')
                ->where('unidad_id', $transfusion->unidad_id)
                ->update(['status' => 'Utilizada']);

            $this->registrarAuditoria(
                $request->user()->usuario_id,
                "Administración de hemocomponente {$transfusion->unidad_id}; transfusión {$transfusionId}",
                'UPDATE'
            );
        });

        return redirect()->route('enfermeria.index')
            ->with('success', 'Transfusión registrada y lote marcado como utilizado.');
    }

    public function storeObservacion(StoreObservacionRequest $request)
    {
        $data = $request->validated();

        if (! empty($data['sesion_id'])) {
            $sesion = ProtocoloSesion::query()->with('ciclo.tratamiento')->findOrFail($data['sesion_id']);
            $pacienteDeSesion = $sesion->ciclo->tratamiento->paciente_id;

            if (! empty($data['paciente_id']) && (int) $data['paciente_id'] !== (int) $pacienteDeSesion) {
                throw ValidationException::withMessages([
                    'paciente_id' => 'El paciente no corresponde a la sesión seleccionada.',
                ]);
            }

            if (! empty($data['sala_id']) && (int) $data['sala_id'] !== (int) $sesion->sala_id) {
                throw ValidationException::withMessages([
                    'sala_id' => 'La sala seleccionada no corresponde a la sesión.',
                ]);
            }

            $data['paciente_id'] = $pacienteDeSesion;
            $data['sala_id'] ??= $sesion->sala_id;
        }

        DB::transaction(function () use ($data, $request): void {
            ObservacionEnfermeria::query()->create([
                ...$data,
                'usuario_id' => $request->user()->usuario_id,
            ]);

            $this->registrarAuditoria($request->user()->usuario_id, 'Registro de observación de Enfermería', 'CREATE');
        });

        return redirect()->route('enfermeria.index')->with('success', 'Observación guardada.');
    }

    public function storeReaccion(StoreReaccionAdversaRequest $request)
    {
        $data = $request->validated();
        $pacienteVinculado = null;

        if (! empty($data['sesion_id'])) {
            $sesion = ProtocoloSesion::query()->with('ciclo.tratamiento')->findOrFail($data['sesion_id']);
            $pacienteVinculado = $sesion->ciclo->tratamiento->paciente_id;
        } else {
            $pacienteVinculado = DB::table('desechable.transfusion')
                ->where('transfusion_id', $data['transfusion_id'])
                ->value('paciente_id');
        }

        if ((int) $pacienteVinculado !== (int) $data['paciente_id']) {
            throw ValidationException::withMessages([
                'paciente_id' => 'El paciente seleccionado no corresponde al tratamiento o transfusión.',
            ]);
        }

        DB::transaction(function () use ($data, $request): void {
            ReaccionAdversa::query()->create([
                ...$data,
                'usuario_id' => $request->user()->usuario_id,
            ]);

            $this->registrarAuditoria($request->user()->usuario_id, 'Registro de reacción adversa', 'CREATE');
        });

        return redirect()->route('enfermeria.index')->with('success', 'Reacción adversa registrada.');
    }

    private function sesionesProgramadas(array $filters, Carbon $date)
    {
        $query = DB::table('logistica.protocolo_sesion as sesion')
            ->join('logistica.protocolo_ciclo as ciclo', 'ciclo.ciclo_id', '=', 'sesion.ciclo_id')
            ->join('logistica.protocolo_tratamiento as tratamiento', 'tratamiento.protocolo_tratamiento_id', '=', 'ciclo.protocolo_tratamiento_id')
            ->join('logistica.protocolo as protocolo', 'protocolo.protocolo_id', '=', 'tratamiento.protocolo_id')
            ->join('logistica.paciente as paciente', 'paciente.paciente_id', '=', 'tratamiento.paciente_id')
            ->join('persona', 'persona.persona_id', '=', 'paciente.persona_id')
            ->join('logistica.medicina_pro as medicina_pro', 'medicina_pro.id', '=', 'sesion.medicina_pro_id')
            ->join('logistica.medicina as medicina', 'medicina.medicina_id', '=', 'medicina_pro.id_medicina')
            ->join('logistica.sala as sala', 'sala.sala_id', '=', 'sesion.sala_id')
            ->whereBetween('sesion.fecha_hora', [$date->copy()->startOfDay(), $date->copy()->endOfDay()])
            ->when($filters['sala_id'] ?? null, fn (Builder $query, $salaId) => $query->where('sesion.sala_id', $salaId))
            ->when($filters['status'] ?? null, fn (Builder $query, $status) => $query->where('sesion.status', $status))
            ->when($filters['paciente'] ?? null, function (Builder $query, string $term): void {
                $pattern = '%' . trim($term) . '%';
                $query->whereRaw(
                    "concat_ws(' ', persona.nombres, persona.apellidos, paciente.hc::text) ILIKE ?",
                    [$pattern]
                );
            })
            ->orderBy('sesion.fecha_hora')
            ->select(
                'sesion.sesion_id',
                'sesion.fecha_hora',
                'sesion.status',
                'sesion.dosis',
                'sesion.via_administracion',
                'sesion.tipo_sesion',
                'sesion.sala_id',
                'sala.nombre as sala',
                'paciente.paciente_id',
                'paciente.hc',
                'persona.nombres',
                'persona.apellidos',
                'protocolo.nombre as protocolo',
                'medicina.descripcion as medicamento',
                'ciclo.numero_ciclo'
            );

        return $query->get();
    }

    private function transfusionesProgramadas(array $filters, Carbon $date): Builder
    {
        return DB::table('desechable.transfusion as transfusion')
            ->join('logistica.paciente as paciente', 'paciente.paciente_id', '=', 'transfusion.paciente_id')
            ->join('persona', 'persona.persona_id', '=', 'paciente.persona_id')
            ->join('desechable.unidad_hemocomponente as unidad', 'unidad.unidad_id', '=', 'transfusion.unidad_id')
            ->leftJoin('logistica.sala as sala', 'sala.sala_id', '=', 'transfusion.sala_id')
            ->whereBetween('transfusion.fecha_hora', [$date->copy()->startOfDay(), $date->copy()->endOfDay()])
            ->where('transfusion.status', 'Programada')
            ->when($filters['sala_id'] ?? null, fn (Builder $query, $salaId) => $query->where('transfusion.sala_id', $salaId))
            ->when($filters['paciente'] ?? null, function (Builder $query, string $term): void {
                $pattern = '%' . trim($term) . '%';
                $query->whereRaw(
                    "concat_ws(' ', persona.nombres, persona.apellidos, paciente.hc::text) ILIKE ?",
                    [$pattern]
                );
            })
            ->orderBy('transfusion.fecha_hora')
            ->select(
                'transfusion.transfusion_id',
                'transfusion.paciente_id',
                'transfusion.unidad_id',
                'transfusion.fecha_hora',
                'transfusion.volumen_adm',
                'transfusion.sala_id',
                'sala.nombre as sala',
                'unidad.codigo_bolsa',
                'unidad.tipo_componente',
                'unidad.grupo_rh',
                'unidad.fecha_vencimiento',
                'paciente.hc',
                'persona.nombres',
                'persona.apellidos'
            );
    }

    private function sesionesActivas()
    {
        return DB::table('logistica.protocolo_sesion as sesion')
            ->join('logistica.protocolo_ciclo as ciclo', 'ciclo.ciclo_id', '=', 'sesion.ciclo_id')
            ->join('logistica.protocolo_tratamiento as tratamiento', 'tratamiento.protocolo_tratamiento_id', '=', 'ciclo.protocolo_tratamiento_id')
            ->join('logistica.paciente as paciente', 'paciente.paciente_id', '=', 'tratamiento.paciente_id')
            ->join('persona', 'persona.persona_id', '=', 'paciente.persona_id')
            ->whereIn('sesion.status', ['Programada', 'Realizada'])
            ->orderByDesc('sesion.fecha_hora')
            ->limit(100)
            ->select(
                'sesion.sesion_id',
                'sesion.status',
                'sesion.fecha_hora',
                'paciente.paciente_id',
                'paciente.hc',
                'persona.nombres',
                'persona.apellidos'
            )
            ->get();
    }

    private function registrarAuditoria(int $usuarioId, string $descripcion, string $accion): void
    {
        Auditoria::query()->create([
            'descripcion' => mb_substr($descripcion, 0, 200),
            'modulo' => 'Enfermería',
            'id_usuario' => $usuarioId,
            'fecha_hora' => now(),
            'accion' => $accion,
        ]);
    }
}
