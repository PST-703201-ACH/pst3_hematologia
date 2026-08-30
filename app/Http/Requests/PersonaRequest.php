<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule; 
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

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
            'cedula' => $isUpdate ? 'nullable' : 'required|numeric|digits_between:7,8|unique:usuario,username',
            'nacionalidad' => $isUpdate ? 'nullable' : 'required|string',
            'telefono' => ['required', 'numeric', 'digits:10', 'regex:/^(424|414|412|416|426)/'], // Valida la longitud y los códigos venezolanos
            'correo' => $isUpdate ? ['required', 'email', Rule::unique('persona', 'email')->ignore($this->input('persona'), 'persona_id')]
    : 'required|email|unique:persona,email',
            'estado' => 'required|integer',
            'municipio' => 'required|integer',
            'parroquia' => 'required|integer',
            'direccion' => ['required', 'regex:/^[a-zA-Z0-9 ]+$/'],
            'rol' => 'required|integer',
            'persona' => $isUpdate ? 'required|exists:persona,persona_id' : 'nullable', // Valida que exista al actualizar
        ];
    }

    // se crea un bloque de codigo para generar mensajes exactos dependiendo de lo necesitado att jeandel XD
    public function messages(): array
    {
        return [
            'fecha_nac.before_or_equal' => 'El nuevo usuario es menor de edad.',
            'telefono.regex' => 'Código de número telefónico inválido.',
            'cedula.unique' => 'Este usuario ya está registrado.',
            // Puedes agregar el resto de tus mensajes personalizados aquí... que si te pica la nariz o x cosa
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        \Illuminate\Support\Facades\Log::info('FAILED VALIDATION CALLED IN PERSONAREQUEST');
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
