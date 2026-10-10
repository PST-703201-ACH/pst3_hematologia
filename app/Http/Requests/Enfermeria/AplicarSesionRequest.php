<?php

namespace App\Http\Requests\Enfermeria;

use Illuminate\Foundation\Http\FormRequest;

class AplicarSesionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return (int) $this->user()?->id_rol === 4;
    }

    public function rules(): array
    {
        return [
            'fecha_hora_aplicacion' => ['required', 'date', 'before_or_equal:now'],
            'identidad_confirmada' => ['accepted'],
            'observaciones_enfermeria' => ['nullable', 'string', 'max:5000'],
        ];
    }

    public function messages(): array
    {
        return [
            'fecha_hora_aplicacion.required' => 'Registre la hora real de aplicación.',
            'identidad_confirmada.accepted' => 'Debe confirmar la identidad del paciente antes de aplicar el tratamiento.',
        ];
    }
}
