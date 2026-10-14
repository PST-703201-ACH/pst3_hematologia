<?php

namespace App\Http\Requests\Medico;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreProtocoloSesionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return (int) $this->user()?->id_rol === 3;
    }

    public function rules(): array
    {
        return [
            'protocolo_tratamiento_id' => ['required', 'integer', Rule::exists('logistica.protocolo_tratamiento', 'protocolo_tratamiento_id')],
            'numero_ciclo' => ['required', 'integer', 'min:1'],
            'medicina_pro_id' => ['required', 'integer', Rule::exists('logistica.medicina_pro', 'id')],
            'sala_nombre' => ['required', 'string', 'max:80'],
            'fecha_hora' => ['required', 'date', 'after_or_equal:now'],
            'tipo_sesion' => ['required', 'string', 'max:50'],
            'dosis' => ['required', 'string', 'max:100'],
            'via_administracion' => ['required', 'string', 'max:100'],
        ];
    }
}
