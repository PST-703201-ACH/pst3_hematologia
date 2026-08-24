<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PacienteRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Preparar los datos para la validación.
     */
    protected function prepareForValidation()
    {
        // une la nacionalidad con la cedula para poder validar que no exista en la base de datos
        // esto se hace porque en la base de datos se guarda la cedula completa
        if ($this->filled('nacionalidad') && $this->filled('cedula')) {
            $this->merge([
                'cedula_completa' => "{$this->nacionalidad}-{$this->cedula}",
            ]);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'hc' => 'required|string|max:30|unique:paciente,hc',
            'nacionalidad' => 'required|string|in:V,E',
            'cedula' => 'required|numeric|digits_between:7,8',
            'cedula_completa' => 'required|string|unique:persona,cedula',
            'nombre1' => 'required|regex:/^[\p{L}\s]+$/u|max:50',
            'nombre2' => 'nullable|regex:/^[\p{L}\s]+$/u|max:50',
            'apellido1' => 'required|regex:/^[\p{L}\s]+$/u|max:50',
            'apellido2' => 'nullable|regex:/^[\p{L}\s]+$/u|max:50',
            'fecha_nacimiento' => 'required|date|before:today',
            'sexo' => 'required|string|in:Masculino,Femenino',
            'email' => 'nullable|email|max:100',
            'telefono' => [
                'required',
                'numeric',
                'digits:10',
                'regex:/^(424|414|412|416|426)\d{7}$/' // Al estar aislado en un array, Laravel no romperá el codigo
            ],
            'estado_id' => 'required|integer|exists:estado,estado_id',
            'municipio_id' => 'nullable|integer|exists:municipio,municipio_id',
            'parroquia_id' => 'nullable|integer|exists:parroquia,parroquia_id',
            'direccion_exacta' => 'nullable|string',
            'representante_id' => 'nullable|integer|exists:representante,representante_id',
            'parentesco' => 'required_with:representante_id|nullable|string|max:50',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'hc.required' => 'El número de Historia Clínica es obligatorio.',
            'hc.unique' => 'Este número de Historia Clínica ya está asignado a otro paciente.',
            'cedula.required' => 'La cédula de identidad es obligatoria.',
            'cedula.numeric' => 'La cédula debe contener solo números.',
            'cedula.digits_between' => 'La cédula debe tener entre 7 y 8 dígitos.',
            'cedula_completa.unique' => 'Esta cédula ya se encuentra registrada en el sistema.',
            'nombre1.required' => 'El primer nombre es obligatorio.',
            'nombre1.regex' => 'El primer nombre solo puede contener letras y espacios.',
            'nombre2.regex' => 'El segundo nombre solo puede contener letras y espacios.',
            'apellido1.required' => 'El primer apellido es obligatorio.',
            'apellido1.regex' => 'El primer apellido solo puede contener letras y espacios.',
            'apellido2.regex' => 'El segundo apellido solo puede contener letras y espacios.',
            'fecha_nacimiento.required' => 'La fecha de nacimiento es obligatoria.',
            'fecha_nacimiento.before' => 'La fecha de nacimiento debe ser anterior al día de hoy.',
            'sexo.required' => 'El sexo es obligatorio.',
            'sexo.in' => 'El sexo seleccionado no es válido.',
            'email.email' => 'El formato del correo electrónico es inválido.',
            'telefono.required' => 'El número de teléfono es obligatorio.',
            'telefono.numeric' => 'El teléfono debe contener solo números.',
            'telefono.digits' => 'El teléfono debe tener exactamente 10 dígitos (sin el 0 inicial).',
            'telefono.regex' => 'El código de área del teléfono no es válido (debe comenzar con 412, 414, 424, 416 o 426).',
            'estado_id.required' => 'El estado es obligatorio.',
            'estado_id.exists' => 'El estado seleccionado no existe.',
            'municipio_id.exists' => 'El municipio seleccionado no existe.',
            'parroquia_id.exists' => 'La parroquia seleccionada no existe.',
            'parentesco.required_with' => 'Debe especificar el parentesco si asocia un representante.',
        ];
    }
}
