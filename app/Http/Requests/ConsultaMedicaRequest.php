<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ConsultaMedicaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'cita_id' => ['required', 'integer', 'exists:cita,cita_id'],
            'es_primera_consulta' => ['required', 'boolean'],
            'tipo_id' => ['required', 'integer', 'exists:tipo_consulta,tipo_id'],
            'enfermedad_id' => ['nullable', 'integer', 'exists:enfermedad,enfermedad_id'],
            'peso' => ['nullable', 'numeric', 'min:0'],
            'talla' => ['nullable', 'numeric', 'min:0'],
            'sc' => ['nullable', 'numeric', 'min:0'],
            'fc' => ['nullable', 'numeric', 'min:0'],
            'fr' => ['nullable', 'numeric', 'min:0'],
            'antecedentes_personales' => ['nullable', 'string'],
            'antecedentes_familiares' => ['nullable', 'string'],
            'signos_sintomas_iniciales' => ['nullable', 'string'],
            'subjetivo' => ['nullable', 'string'],
            'plan_trabajo' => ['nullable', 'string'],
            'proxima_cita' => ['nullable', 'date'],
        ];
    }

    public function messages(): array
    {
        return [
            'cita_id.required' => 'Debe seleccionar una cita válida.',
            'tipo_id.required' => 'Debe seleccionar el tipo de consulta.',
            'tipo_id.exists' => 'El tipo de consulta seleccionado no existe.',
            'enfermedad_id.exists' => 'La enfermedad seleccionada no existe.',
            'peso.numeric' => 'El peso debe ser numérico.',
            'talla.numeric' => 'La talla debe ser numérica.',
            'sc.numeric' => 'La superficie corporal debe ser numérica.',
            'fc.numeric' => 'La frecuencia cardíaca debe ser numérica.',
            'fr.numeric' => 'La frecuencia respiratoria debe ser numérica.',
            'proxima_cita.date' => 'La próxima cita debe tener un formato de fecha válido.',
        ];
    }
}
