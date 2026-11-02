<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule; 
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use App\Models\User;

class PersonaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool // metodo que determina si el usuario esta autorizado para hacer la peticion
    { // en este caso si esta autorizado para hacer la peticion
        return true; // lo que devuelve true o false dependiendo de la autorizacion
    }

    public function rules(): array
    {
        $isUpdate = $this->has('persona') && !empty($this->input('persona'));

        return [ // reglas de validacion para los diferentes metodos
            'nombre1' => 'required|regex:/^[\p{L}\s]+$/u',
            'nombre2' => 'nullable|regex:/^[\p{L}\s]+$/u',
            'apellido1' => 'required|regex:/^[\p{L}\s]+$/u',
            'apellido2' => 'nullable|regex:/^[\p{L}\s]+$/u',
            'tipo_reg' => $isUpdate ? 'nullable' : 'required|string',
            'tipo_up' => $isUpdate ? 'required|string' : 'nullable',
            'fecha_nac' => 'required|date|before_or_equal:-18 years', // Controla los 18 años automáticamente
            'sexo' => 'required|string',
            'cedula' => $isUpdate ? 'nullable' : [
                'required',
                'numeric',
                'digits_between:7,8',
                Rule::unique(User::class, 'username'),
                Rule::unique('persona', 'cedula'),
            ],
            'nacionalidad' => $isUpdate ? 'nullable' : 'required|string',
            'telefono' => ['required', 'numeric', 'digits:10', 'regex:/^(424|414|412|416|426)/'], // Valida la longitud y los códigos venezolanos
            'correo' => $isUpdate ? ['required', 'email', Rule::unique('persona', 'email')->ignore($this->input('persona'), 'persona_id')]
    : 'required|email|unique:persona,email',
            'estado' => 'required|integer',
            'municipio' => 'required|integer',
            'parroquia' => 'required|integer',
            'direccion' => ['required', 'regex:/^[\p{L}\p{N}\s.,#\-\/]+$/u'],
            'rol' => 'required|integer',
            'persona' => $isUpdate ? 'required|exists:persona,persona_id' : 'nullable', // Valida que exista al actualizar
        ];
    }

    public function messages(): array
    {
        return [
            'required' => 'El campo :attribute es obligatorio.',
            'date' => 'El campo :attribute debe ser una fecha válida.',
            'before_or_equal' => 'El usuario debe tener al menos 18 años.',
            'regex' => 'El campo :attribute contiene caracteres o un formato no válido.',
            'email' => 'Ingrese un correo electrónico válido.',
            'numeric' => 'El campo :attribute debe contener solo números.',
            'digits' => 'El campo :attribute debe tener exactamente :digits dígitos.',
            'digits_between' => 'El campo :attribute debe tener entre :min y :max dígitos.',
            'unique' => 'El :attribute ya está registrado.',
            'integer' => 'Seleccione un :attribute válido.',
            'exists' => 'El :attribute seleccionado no es válido.',
            'string' => 'El campo :attribute debe ser texto.',
            'cedula.unique' => 'Esta cédula ya está registrada.',
            'correo.unique' => 'Este correo electrónico ya está registrado.',
            'telefono.regex' => 'El teléfono debe iniciar con un código móvil válido: 412, 414, 416, 424 o 426.',
        ];
    }

    public function attributes(): array
    {
        return [
            'nombre1' => 'primer nombre',
            'nombre2' => 'segundo nombre',
            'apellido1' => 'primer apellido',
            'apellido2' => 'segundo apellido',
            'tipo_reg' => 'tipo de registro',
            'tipo_up' => 'tipo de actualización',
            'fecha_nac' => 'fecha de nacimiento',
            'sexo' => 'sexo',
            'cedula' => 'cédula',
            'nacionalidad' => 'nacionalidad',
            'telefono' => 'teléfono',
            'correo' => 'correo electrónico',
            'estado' => 'estado',
            'municipio' => 'municipio',
            'parroquia' => 'parroquia',
            'direccion' => 'dirección',
            'rol' => 'rol',
            'persona' => 'persona',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $response = response()->json([
            'status' => 'errores',
            'errores' => $validator->errors()->messages(),
        ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY);

        throw new HttpResponseException($response);
    }

    /**
     * Get the validation rules that apply to the request.
     * estos son solos metodos de laravel, si se quiere se borra XD
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
}
