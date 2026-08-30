<?php

namespace App\Http\Controllers;

use App\Http\Requests\PacienteRequest;
use App\Models\Estado;
use App\Models\Paciente;
use App\Models\Consulta;
use App\Models\Persona;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MedicoController extends Controller
{

    public function index()
    {
        $pacientes = Paciente::with('persona')->get();

        return view('medico.pacientes.index', compact('pacientes'));
    }


    public function listar()
    {
        $consultas = Consulta::with('paciente')->get();

        return response()->json($pacientes);
    }

    public function listarCon()
    {
        $pacientes = Consulta::with('paciente.persona', 'enfermedad')->where('status', 0)->get();

        return response()->json($pacientes);
    }

    /**
     * Muestra el formulario para registrar un nuevo paciente.
     */
    public function create()
    {
        $estados = Estado::all();

        $representantes = class_exists('\\App\\Models\\Representante')
            ? \App\Models\Representante::with('persona')->take(50)->get()
            : collect();

        return view('medico.pacientes.create', compact('estados', 'representantes'));
    }

    /**
     * Almacena un nuevo paciente (Persona + Datos Paciente + Representante).
     */
    public function store(PacienteRequest $request)
    {
        try {
            DB::beginTransaction();

            // 1. Formatear nombres y apellidos (primer + segundo)
            $nombres = trim($request->nombre1 . ($request->nombre2 ? ' ' . $request->nombre2 : ''));
            $apellidos = trim($request->apellido1 . ($request->apellido2 ? ' ' . $request->apellido2 : ''));

            // 2. Crear Registro en Persona (siguiendo max + 1)
            $nextPersonaId = (Persona::max('persona_id') ?? 0) + 1;

            $persona = Persona::create([
                'persona_id' => $nextPersonaId,
                'cedula' => "{$request->nacionalidad}-{$request->cedula}",
                'nombres' => $nombres,
                'apellidos' => $apellidos,
                'fecha_nacimiento' => $request->fecha_nacimiento,
                'sexo' => $request->sexo,
                'telefono' => $request->telefono ? "0{$request->telefono}" : null,
                'email' => $request->email,
                'estado_id' => $request->estado_id,
                'municipio_id' => $request->municipio_id,
                'parroquia_id' => $request->parroquia_id,
                'direccion_exacta' => $request->direccion_exacta,
                'status' => 'Activo',
            ]);

            if (! $persona) {
                throw new \Exception('No se pudo crear el registro de Persona.');
            }

            // 3. Crear Registro en Paciente (siguiendo max + 1)
            $nextPacienteId = (Paciente::max('paciente_id') ?? 0) + 1;

            $paciente = Paciente::create([
                'paciente_id' => $nextPacienteId,
                'persona_id' => $persona->persona_id,
                'hc' => $request->hc,
                'status' => 'Activo',
            ]);

            if (! $paciente) {
                throw new \Exception('No se pudo crear el registro de Paciente.');
            }

            // 4. Si se envió representante_id y el modelo existe, asociar al paciente en la tabla pivot
            if ($request->filled('representante_id') && method_exists($paciente, 'representantes')) {
                $paciente->representantes()->attach($request->representante_id, [
                    'parentesco' => $request->parentesco,
                    'es_principal' => true,
                ]);
            }

            DB::commit();

            return redirect()->route('medico.pacientes.show', $paciente->paciente_id)
                ->with('success', "Paciente registrado exitosamente. Historia Clínica N°: {$paciente->hc}");

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error crítico al registrar paciente: '.$e->getMessage(), [
                'request' => $request->all(),
                'trace' => $e->getTraceAsString(),
            ]);

            return back()->withInput()
                ->withErrors(['error' => 'No se pudo completar el registro: '.$e->getMessage()]);
        }
    }

    /**
     * Muestra la vista de detalle individual de un paciente.
     */
    public function show($id)
    {
        $with = [
            'persona.estado',
            'persona.municipio',
            'persona.parroquia',
        ];

        $paciente = Paciente::with($with)->findOrFail($id);

        return view('medico.pacientes.show', compact('paciente'));
    }

    /**
     * Retorna un listado JSON de representantes para búsquedas dinámicas con Select2.
     */
    public function getRepresentantes(Request $request)
    {
        if (! class_exists('\\App\\Models\\Representante')) {
            return response()->json([]);
        }

        $search = $request->get('q');

        $query = \App\Models\Representante::with('persona');

        if ($search) {
            $query->whereHas('persona', function ($q) use ($search) {
                $q->where('nombres', 'like', "%{$search}%")
                  ->orWhere('apellidos', 'like', "%{$search}%")
                  ->orWhere('cedula', 'like', "%{$search}%");
            });
        }

        $representantes = $query->take(20)->get();

        $results = $representantes->map(function ($rep) {
            return [
                'id' => $rep->representante_id,
                'text' => "{$rep->persona->cedula} - {$rep->persona->nombres} {$rep->persona->apellidos}",
            ];
        });

        return response()->json($results);
    }

    /**
     * Elimina un paciente y su información personal (Temporal).
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $paciente = Paciente::findOrFail($id);
            $personaId = $paciente->persona_id;

            if (method_exists($paciente, 'representantes')) {
                $paciente->representantes()->detach();
            }

            // Eliminamos primero al paciente por la FK
            $paciente->delete();

            // Eliminamos la persona asociada
            Persona::where('persona_id', $personaId)->delete();

            DB::commit();

            return redirect()->route('medico.pacientes.index')
                ->with('success', 'Registro eliminado correctamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al eliminar paciente: '.$e->getMessage());

            return back()->withErrors(['error' => 'No se pudo eliminar el registro: '.$e->getMessage()]);
        }
    }
}
