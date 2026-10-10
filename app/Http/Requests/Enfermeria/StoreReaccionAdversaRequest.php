<?php

namespace App\Http\Requests\Enfermeria;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class StoreReaccionAdversaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return (int) $this->user()?->id_rol === 4;
    }

    public function rules(): array
    {
        return [
            'paciente_id' => ['required', 'integer', Rule::exists('logistica.paciente', 'paciente_id')],
            'sesion_id' => ['nullable', 'integer', Rule::exists('logistica.protocolo_sesion', 'sesion_id')],
            'transfusion_id' => ['nullable', 'integer', Rule::exists('desechable.transfusion', 'transfusion_id')],
            'categoria' => ['required', Rule::in(['medicamento', 'hemocomponente', 'otra'])],
            'gravedad' => ['required', Rule::in(['baja', 'moderada', 'alta', 'critica'])],
            'fecha_hora' => ['required', 'date', 'before_or_equal:now'],
            'descripcion' => ['required', 'string', 'max:5000'],
            'intervencion' => ['nullable', 'string', 'max:5000'],
        ];
    }

    public function after(): array
    {
        return [
            function (Validator $validator): void {
                if (blank($this->input('sesion_id')) && blank($this->input('transfusion_id'))) {
                    $validator->errors()->add('sesion_id', 'Asocie la reacción a una sesión o transfusión.');
                }

                if (filled($this->input('sesion_id')) && filled($this->input('transfusion_id'))) {
                    $validator->errors()->add('transfusion_id', 'Seleccione una sola sesión o transfusión.');
                }
            },
        ];
    }

    public function messages(): array
    {
        return [
            'descripcion.required' => 'Describa la reacción adversa.',
            'fecha_hora.required' => 'Indique cuándo ocurrió la reacción.',
        ];
    }
}
