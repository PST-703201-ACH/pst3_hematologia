<?php

namespace App\Http\Requests\Enfermeria;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreObservacionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return (int) $this->user()?->id_rol === 4;
    }

    public function rules(): array
    {
        return [
            'sala_id' => ['nullable', 'integer', Rule::exists('logistica.sala', 'sala_id')],
            'paciente_id' => ['nullable', 'integer', Rule::exists('logistica.paciente', 'paciente_id')],
            'sesion_id' => ['nullable', 'integer', Rule::exists('logistica.protocolo_sesion', 'sesion_id')],
            'categoria' => ['required', Rule::in(['clinica', 'operativa', 'otra'])],
            'gravedad' => ['required', Rule::in(['baja', 'moderada', 'alta', 'critica'])],
            'fecha_hora' => ['required', 'date', 'before_or_equal:now'],
            'descripcion' => ['required', 'string', 'max:5000'],
            'accion_tomada' => ['nullable', 'string', 'max:5000'],
        ];
    }

    public function messages(): array
    {
        return [
            'descripcion.required' => 'Describa la incidencia observada.',
            'fecha_hora.required' => 'Indique cuándo ocurrió la incidencia.',
        ];
    }
}
