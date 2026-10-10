<?php

namespace App\Http\Requests\Enfermeria;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PanelEnfermeriaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return (int) $this->user()?->id_rol === 4;
    }

    public function rules(): array
    {
        return [
            'fecha' => ['nullable', 'date'],
            'sala_id' => ['nullable', 'integer', Rule::exists('logistica.sala', 'sala_id')],
            'status' => ['nullable', Rule::in(['Programada', 'Realizada', 'Suspendida', 'Cancelada'])],
            'paciente' => ['nullable', 'string', 'max:100'],
        ];
    }
}
