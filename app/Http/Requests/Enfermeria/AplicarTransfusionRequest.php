<?php

namespace App\Http\Requests\Enfermeria;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AplicarTransfusionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return (int) $this->user()?->id_rol === 4;
    }

    public function rules(): array
    {
        return [
            'fecha_hora_aplicacion' => ['required', 'date', 'before_or_equal:now'],
            'sala_id' => ['required', 'integer', Rule::exists('logistica.sala', 'sala_id')],
            'identidad_confirmada' => ['accepted'],
        ];
    }

    public function messages(): array
    {
        return [
            'identidad_confirmada.accepted' => 'Debe confirmar la identidad del paciente antes de iniciar la transfusión.',
        ];
    }
}
