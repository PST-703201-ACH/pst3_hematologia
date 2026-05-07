<?php

namespace App\Http\Controllers\Medico;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Persona;
use App\Models\Estado;
use App\Models\Paciente;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PacienteController extends Controller
{
    /**
     * Muestra el listado de pacientes registrados.
     */
    public function index()
    {
        $pacientes = Paciente::with('persona')->get();
        return view('medico.pacientes.index', compact('pacientes'));
    }

    /**
     * Muestra el formulario para registrar un nuevo paciente.
     */
    public function create()
    {
        $estados = Estado::all(); // Asumiendo que existe el modelo Estado
        return view('medico.pacientes.create', compact('estados'));
    }

    /**
     * Almacena un nuevo paciente (Persona + Datos Paciente).
     */
    public function store(Request $request)
    {
        // 1. Validación exhaustiva de datos
        $validated = $request->validate([
            'hc' => 'required|string|max:30|unique:paciente,hc',
            'cedula' => 'required|string|max:15|unique:persona,cedula',
            'nombres' => 'required|string|max:100',
            'apellidos' => 'required|string|max:100',
            'fecha_nacimiento' => 'required|date|before:today',
            'sexo' => 'required|string|in:Masculino,Femenino',
            'email' => 'nullable|email|max:100',
            'telefono' => 'nullable|string|max:20',
            'estado_id' => 'required|integer',
            'municipio_id' => 'nullable|integer',
            'parroquia_id' => 'nullable|integer',
            'direccion_exacta' => 'nullable|string',
        ], [
            'hc.unique' => 'Este número de Historia Clínica ya está asignado a otro paciente.',
            'cedula.unique' => 'Esta cédula ya se encuentra registrada en el sistema.',
            'fecha_nacimiento.before' => 'La fecha de nacimiento no puede ser futura.',
            'sexo.in' => 'El valor del campo sexo no es válido.',
        ]);

        try {
            DB::beginTransaction();

            // 2. Crear Registro en Persona
            $nextPersonaId = (Persona::max('persona_id') ?? 0) + 1;
            
            $persona = Persona::create([
                'persona_id' => $nextPersonaId,
                'cedula' => $validated['cedula'],
                'nombres' => $validated['nombres'],
                'apellidos' => $validated['apellidos'],
                'fecha_nacimiento' => $validated['fecha_nacimiento'],
                'sexo' => $validated['sexo'],
                'telefono' => $validated['telefono'],
                'email' => $validated['email'],
                'estado_id' => $validated['estado_id'],
                'municipio_id' => $validated['municipio_id'],
                'parroquia_id' => $validated['parroquia_id'],
                'direccion_exacta' => $validated['direccion_exacta'],
                'status' => 'Activo',
            ]);

            if (!$persona) {
                throw new \Exception("No se pudo crear el registro de Persona.");
            }

            // 3. Crear Registro en Paciente
            $nextPacienteId = (Paciente::max('paciente_id') ?? 0) + 1;
            
            $paciente = Paciente::create([
                'paciente_id' => $nextPacienteId,
                'persona_id' => $persona->persona_id,
                'hc' => $validated['hc'], // Usamos el valor que mandó el médico
                'status' => 'Activo',
            ]);

            if (!$paciente) {
                throw new \Exception("No se pudo crear el registro de Paciente.");
            }

            DB::commit();

            return redirect()->route('medico.pacientes.index')
                ->with('success', "Paciente registrado exitosamente. Historia Clínica N°: {$validated['hc']}");

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error crítico al registrar paciente: ' . $e->getMessage(), [
                'request' => $request->all(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return back()->withInput()
                ->withErrors(['error' => 'No se pudo completar el registro: ' . $e->getMessage()]);
        }
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

            // Eliminamos primero al paciente por la FK
            $paciente->delete();
            
            // Eliminamos la persona asociada
            Persona::where('persona_id', $personaId)->delete();

            DB::commit();

            return redirect()->route('medico.pacientes.index')
                ->with('success', 'Registro eliminado correctamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al eliminar paciente: ' . $e->getMessage());
            return back()->withErrors(['error' => 'No se pudo eliminar el registro: ' . $e->getMessage()]);
        }
    }
}
