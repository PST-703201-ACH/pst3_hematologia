<?php

namespace App\Http\Requests\Medico;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreProtocoloTratamientoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return (int) $this->user()?->id_rol === 3;
    }

    public function rules(): array
    {
        return [
            'paciente_id' => ['required', 'integer', Rule::exists('logistica.paciente', 'paciente_id')],
            'consulta_id' => ['required', 'integer', Rule::exists('logistica.consulta', 'consulta_id')],
            'protocolo_id' => ['required', 'integer', Rule::exists('logistica.protocolo', 'protocolo_id')],
            'fecha_inicio' => ['required', 'date'],
            'indicaciones' => ['nullable', 'string', 'max:5000'],
        ];
    }
}
