<?php

namespace App\Http\Controllers;

use App\Http\Requests\ConsultaMedicaRequest;
use App\Models\Cita;
use App\Models\Consulta;
use App\Models\Enfermedad;
use App\Models\Paciente;
use App\Models\Persona;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ConsultaController extends Controller
{
    public function atenderCita($citaId)
    {
        $cita = Cita::with('consulta')->findOrFail($citaId);

        $paciente = null;
        $persona = null;
        $esPrimeraConsulta = false;

        if (!empty($cita->numero_hc)) {
            $paciente = Paciente::where('hc', $cita->numero_hc)->first();
        }

        if ($paciente) {
            $persona = $paciente->persona;
            $esPrimeraConsulta = !Consulta::where('paciente_id', $paciente->paciente_id)->exists();
        } else {
            $esPrimeraConsulta = true;
        }

        $tipoConsultaId = $esPrimeraConsulta ? 1 : 2;
        $tipoConsultaNombre = DB::table('tipo_consulta')
            ->where('tipo_id', $tipoConsultaId)
            ->value('nombre') ?? ($esPrimeraConsulta ? 'Primera consulta' : 'Consulta de control');
        $enfermedades = Enfermedad::orderBy('enfermedad_id')->get();
        $medico = auth()->user();

        $vistaData = [
            'cita' => $cita,
            'paciente' => $paciente,
            'persona' => $persona,
            'esPrimeraConsulta' => $esPrimeraConsulta,
            'tipoConsultaId' => $tipoConsultaId,
            'tipoConsultaNombre' => $tipoConsultaNombre,
            'enfermedades' => $enfermedades,
            'medico' => $medico,
            'titulo' => $esPrimeraConsulta ? 'Primera Consulta' : 'Consulta de Control',
        ];

        return view('medico.consultas.formulario', $vistaData);
    }

    public function store(ConsultaMedicaRequest $request)
    {
        try {
            DB::beginTransaction();

            $cita = Cita::findOrFail($request->cita_id);
            $paciente = null;
            $paciente = Paciente::where('hc', $cita->numero_hc)->first();
            $esPrimeraConsulta = ! $paciente || ! Consulta::where('paciente_id', $paciente->paciente_id)->exists();
            $tipoConsultaId = $esPrimeraConsulta ? 1 : 2;

            if ($esPrimeraConsulta) {
                $paciente = $this->crearPacienteDesdeCita($cita);
            } else {
                $paciente = $paciente->fresh();
            }

            $consulta = Consulta::create([
                'paciente_id' => $paciente->paciente_id,
                'medico_id' => auth()->user()?->usuario_id,
                'tipo_id' => $tipoConsultaId,
                'enfermedad_id' => $request->enfermedad_id,
                'fecha_hora' => now(),
                'peso' => $request->peso,
                'talla' => $request->talla,
                'sc' => $request->sc,
                'fc' => $request->fc,
                'fr' => $request->fr,
                'antecedentes_personales' => $request->antecedentes_personales,
                'antecedentes_familiares' => $request->antecedentes_familiares,
                'signos_sintomas_iniciales' => $request->signos_sintomas_iniciales,
                'subjetivo' => $request->subjetivo,
                'plan_trabajo' => $request->plan_trabajo,
                'proxima_cita' => $request->proxima_cita,
                'status' => 1,
            ]);

            if ($cita->consulta_id) {
                $consultaExistente = Consulta::find($cita->consulta_id);
                if ($consultaExistente) {
                    $consultaExistente->update(['status' => 1]);
                }
            }

            $cita->update([
                'consulta_id' => $consulta->consulta_id,
                'estatus' => 'atendida',
            ]);

            DB::commit();

            return redirect()->route('medico.index', ['vista' => 'consultas'])
                ->with('success', 'Consulta médica registrada exitosamente.');
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Error al guardar consulta médica: ' . $e->getMessage(), [
                'request' => $request->all(),
                'trace' => $e->getTraceAsString(),
            ]);

            return back()->withInput()->withErrors([
                'error' => 'No se pudo guardar la consulta: ' . $e->getMessage(),
            ]);
        }
    }

    protected function crearPacienteDesdeCita(Cita $cita): Paciente
    {
        $paciente = Paciente::where('hc', $cita->numero_hc)->first();

        if ($paciente) {
            return $paciente;
        }

        $persona = Persona::create([
            'nombres' => trim(($cita->nombres_paciente ?? '')),
            'apellidos' => trim(($cita->apellidos_paciente ?? '')),
            'cedula' => $cita->numero_hc,
            'telefono' => null,
            'email' => null,
            'estado_id' => 1,
            'municipio_id' => 1,
            'parroquia_id' => 1,
            'status' => 'Activo',
        ]);

        if (! $persona) {
            throw new \RuntimeException('No se pudo crear la persona del paciente.');
        }

        $paciente = Paciente::create([
            'persona_id' => $persona->persona_id,
            'hc' => $cita->numero_hc,
            'status' => 1,
        ]);

        if (! $paciente) {
            throw new \RuntimeException('No se pudo crear el paciente.');
        }

        return $paciente;
    }
}
