<?php

use App\Http\Controllers\PersonaController;
use App\Http\Requests\PersonaRequest;
use App\Models\Persona;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

uses(RefreshDatabase::class);

beforeEach(function () {
    Mail::fake();

    DB::table('estado')->insert(['estado_id' => 1, 'nombre' => 'Caracas']);
    DB::table('municipio')->insert(['municipio_id' => 1, 'estado_id' => 1, 'nombre' => 'Libertador']);
    DB::table('parroquia')->insert(['parroquia_id' => 1, 'municipio_id' => 1, 'nombre' => 'La Vega']);
    DB::table('rol')->insert(['rol_id' => 1, 'nombre' => 'Administrador']);
});

it('does not create a person or user when the registration type is unsupported', function () {
    $request = new PersonaRequest();
    $request->merge([
        'nombre1' => 'Ana',
        'nombre2' => 'Maria',
        'apellido1' => 'Pérez',
        'apellido2' => 'García',
        'tipo_reg' => 'paciente',
        'fecha_nac' => '1995-01-20',
        'sexo' => 'F',
        'cedula' => '12345678',
        'nacionalidad' => 'V',
        'telefono' => '4141234567',
        'correo' => 'ana@example.com',
        'estado' => 1,
        'municipio' => 1,
        'parroquia' => 1,
        'direccion' => 'Calle 1',
        'rol' => 1,
    ]);

    $response = app(PersonaController::class)->registrar($request);

    expect(Persona::count())->toBe(0)
        ->and(User::count())->toBe(0)
        ->and($response->getData(true)['status'])->toBe('error');
});

it('creates the person and user when the registration type is valid', function () {
    $request = new PersonaRequest();
    $request->merge([
        'nombre1' => 'Ana',
        'nombre2' => 'Maria',
        'apellido1' => 'Pérez',
        'apellido2' => 'García',
        'tipo_reg' => 'usuario',
        'fecha_nac' => '1995-01-20',
        'sexo' => 'F',
        'cedula' => '12345678',
        'nacionalidad' => 'V',
        'telefono' => '4141234567',
        'correo' => 'ana@example.com',
        'estado' => 1,
        'municipio' => 1,
        'parroquia' => 1,
        'direccion' => 'Calle 1',
        'rol' => 1,
    ]);

    $response = app(PersonaController::class)->registrar($request);

    $persona = Persona::first();
    $usuario = User::first();

    expect($response->getData(true)['status'])->toBe('exito')
        ->and(Persona::count())->toBe(1)
        ->and(User::count())->toBe(1)
        ->and($persona->cedula)->toBe('V-12345678')
        ->and($persona->telefono)->toBe('04141234567')
        ->and($usuario->persona_id)->toBe($persona->persona_id)
        ->and($usuario->id_rol)->toBe(1);

    Mail::assertSent(\App\Mail\UsuarioNuevo::class);
});

it('does not update the person or user when the update type is unsupported', function () {
    $persona = Persona::create([
        'nombres' => 'Ana',
        'apellidos' => 'Pérez',
        'fecha_nacimiento' => '1995-01-20',
        'sexo' => 'F',
        'cedula' => 'V-12345678',
        'telefono' => '04141234567',
        'email' => 'ana@example.com',
        'estado_id' => 1,
        'municipio_id' => 1,
        'parroquia_id' => 1,
        'direccion_exacta' => 'Calle 1',
    ]);

    $usuario = User::create([
        'username' => '12345678',
        'password_hash' => bcrypt('123456'),
        'persona_id' => $persona->persona_id,
        'id_rol' => 1,
        'status' => 1,
    ]);

    $request = new PersonaRequest();
    $request->merge([
        'persona' => $persona->persona_id,
        'nombre1' => 'Ana',
        'nombre2' => 'María',
        'apellido1' => 'Pérez',
        'apellido2' => 'García',
        'tipo_up' => 'paciente',
        'fecha_nac' => '1995-01-20',
        'sexo' => 'F',
        'telefono' => '4141234567',
        'correo' => 'ana@example.com',
        'estado' => 1,
        'municipio' => 1,
        'parroquia' => 1,
        'direccion' => 'Avenida 2',
        'rol' => 2,
    ]);

    $response = app(PersonaController::class)->actualizar($request);

    $persona->refresh();
    $usuario->refresh();

    expect($response->getData(true)['status'])->toBe('error')
        ->and($persona->direccion_exacta)->toBe('Calle 1')
        ->and($usuario->id_rol)->toBe(1);
});
