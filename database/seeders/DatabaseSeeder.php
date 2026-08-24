<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('estado')->insert([
            ['estado_id' => 1, 'nombre' => 'Distrito Capital'],
            ['estado_id' => 2, 'nombre' => 'Miranda'],
        ]);

        DB::table('rol')->insert([
            ['rol_id' => 1, 'nombre' => 'Administrador'],
            ['rol_id' => 2, 'nombre' => 'Administrativo'],
            ['rol_id' => 3, 'nombre' => 'Medico'],
            ['rol_id' => 4, 'nombre' => 'Enfermero'],
        ]);

        DB::table('tipo_consulta')->insert([
            ['tipo_id' => 1, 'nombre' => 'Primera consulta'],
            ['tipo_id' => 2, 'nombre' => 'Control'],
        ]);

        DB::table('enfermedad')->insert([
            ['enfermedad_id' => 1, 'tipo' => true, 'descripcion' => 'Anemia falciforme'],
            ['enfermedad_id' => 2, 'tipo' => false, 'descripcion' => 'Leucemia linfoblastica aguda'],
        ]);

        DB::table('examen')->insert([
            ['examen_id' => 1, 'nombre' => 'Hemoglobina', 'unidad_medida' => 'g/dL', 'valor_ref_min' => 11.5, 'valor_ref_max' => 15.5, 'status' => 'Activo'],
            ['examen_id' => 2, 'nombre' => 'Plaquetas', 'unidad_medida' => 'mil/uL', 'valor_ref_min' => 150, 'valor_ref_max' => 450, 'status' => 'Activo'],
        ]);

        DB::table('municipio')->insert([
            ['municipio_id' => 1, 'estado_id' => 1, 'nombre' => 'Libertador'],
            ['municipio_id' => 2, 'estado_id' => 2, 'nombre' => 'Chacao'],
        ]);

        DB::table('parroquia')->insert([
            ['parroquia_id' => 1, 'municipio_id' => 1, 'nombre' => 'San Bernardino'],
            ['parroquia_id' => 2, 'municipio_id' => 1, 'nombre' => 'La Candelaria'],
            ['parroquia_id' => 3, 'municipio_id' => 2, 'nombre' => 'Chacao'],
        ]);

        DB::table('persona')->insert([
            ['persona_id' => 1, 'nombres' => 'Ana', 'apellidos' => 'Gonzalez', 'fecha_nacimiento' => '1985-04-12', 'sexo' => 'F', 'cedula' => '10000001', 'telefono' => '04140000001', 'email' => 'ana.gonzalez@example.com', 'estado_id' => 1, 'municipio_id' => 1, 'parroquia_id' => 1, 'direccion_exacta' => 'San Bernardino, Caracas'],
            ['persona_id' => 2, 'nombres' => 'Luis', 'apellidos' => 'Rojas', 'fecha_nacimiento' => '1978-09-20', 'sexo' => 'M', 'cedula' => '10000002', 'telefono' => '04140000002', 'email' => 'luis.rojas@example.com', 'estado_id' => 1, 'municipio_id' => 1, 'parroquia_id' => 2, 'direccion_exacta' => 'La Candelaria, Caracas'],
            ['persona_id' => 3, 'nombres' => 'Maria', 'apellidos' => 'Perez', 'fecha_nacimiento' => '1990-01-15', 'sexo' => 'F', 'cedula' => '10000003', 'telefono' => '04140000003', 'email' => 'maria.perez@example.com', 'estado_id' => 2, 'municipio_id' => 2, 'parroquia_id' => 3, 'direccion_exacta' => 'Chacao, Miranda'],
            ['persona_id' => 4, 'nombres' => 'Carlos', 'apellidos' => 'Rodriguez', 'fecha_nacimiento' => '2014-06-08', 'sexo' => 'M', 'cedula' => '10000004', 'telefono' => '04140000004', 'email' => 'carlos.representante@example.com', 'estado_id' => 1, 'municipio_id' => 1, 'parroquia_id' => 1, 'direccion_exacta' => 'San Bernardino, Caracas'],
            ['persona_id' => 5, 'nombres' => 'Sofia', 'apellidos' => 'Martinez', 'fecha_nacimiento' => '2016-11-23', 'sexo' => 'F', 'cedula' => '10000005', 'telefono' => '04140000005', 'email' => 'sofia.paciente@example.com', 'estado_id' => 1, 'municipio_id' => 1, 'parroquia_id' => 2, 'direccion_exacta' => 'La Candelaria, Caracas'],
        ]);

        DB::table('usuario')->insert([
            ['usuario_id' => 1, 'persona_id' => 1, 'username' => '10000001', 'password_hash' => Hash::make('Password123!'), 'status' => 1, 'id_rol' => 1],
            ['usuario_id' => 2, 'persona_id' => 2, 'username' => '10000002', 'password_hash' => Hash::make('Password123!'), 'status' => 1, 'id_rol' => 2],
            ['usuario_id' => 3, 'persona_id' => 3, 'username' => '10000003', 'password_hash' => Hash::make('Password123!'), 'status' => 1, 'id_rol' => 3],
            ['usuario_id' => 4, 'persona_id' => 4, 'username' => '10000004', 'password_hash' => Hash::make('Password123!'), 'status' => 1, 'id_rol' => 4],
        ]);

        DB::table('paciente')->insert([
            ['paciente_id' => 1, 'persona_id' => 5, 'hc' => 'HC-000001', 'status' => 1],
            ['paciente_id' => 2, 'persona_id' => 4, 'hc' => 'HC-000002', 'status' => 0],
        ]);

        DB::table('representante')->insert([
            ['representante_id' => 1, 'persona_id' => 1, 'ocupacion' => 'Docente'],
            ['representante_id' => 2, 'persona_id' => 2, 'ocupacion' => 'Ingeniero'],
        ]);

        DB::table('paciente_representante')->insert([
            ['paciente_id' => 1, 'representante_id' => 1, 'parentesco' => 'Madre', 'es_principal' => true],
            ['paciente_id' => 2, 'representante_id' => 2, 'parentesco' => 'Padre', 'es_principal' => true],
        ]);

        DB::table('consulta')->insert([
            ['consulta_id' => 1, 'paciente_id' => 1, 'medico_id' => 3, 'tipo_id' => 1, 'enfermedad_id' => 1, 'fecha_hora' => '2026-08-20 08:30:00', 'peso' => 28.5, 'talla' => 128.0, 'sc' => 1.01, 'fc' => 92, 'fr' => 20, 'subjetivo' => 'Fatiga ocasional.', 'plan_trabajo' => 'Solicitar hemograma de control.', 'proxima_cita' => '2026-09-20', 'status' => 1],
            ['consulta_id' => 2, 'paciente_id' => 2, 'medico_id' => 3, 'tipo_id' => 2, 'enfermedad_id' => 2, 'fecha_hora' => '2026-08-21 10:00:00', 'peso' => 32.0, 'talla' => 135.0, 'sc' => 1.10, 'fc' => 88, 'fr' => 18, 'subjetivo' => 'Control hematologico.', 'plan_trabajo' => 'Continuar protocolo y vigilancia.', 'proxima_cita' => '2026-09-21', 'status' => 1],
        ]);

        DB::table('antecedente_familiar')->insert([
            ['ant_fam_id' => 1, 'paciente_id' => 1, 'consulta_id' => 1, 'parentesco' => 'Madre', 'patologia' => 'Rasgo falciforme', 'descripcion' => 'Antecedente familiar referido.'],
            ['ant_fam_id' => 2, 'paciente_id' => 2, 'consulta_id' => 2, 'parentesco' => 'Tio', 'patologia' => 'Leucemia', 'descripcion' => 'Antecedente oncologico familiar.'],
        ]);

        DB::table('cita')->insert([
            ['cita_id' => 1, 'nombres_paciente' => 'Sofia', 'apellidos_paciente' => 'Martinez', 'nombres_representante' => 'Ana', 'apellidos_representante' => 'Gonzalez', 'numero_hc' => 1, 'fecha_hora' => '2026-09-20 09:00:00', 'consulta_id' => 1],
            ['cita_id' => 2, 'nombres_paciente' => 'Carlos', 'apellidos_paciente' => 'Rodriguez', 'nombres_representante' => 'Luis', 'apellidos_representante' => 'Rojas', 'numero_hc' => 2, 'fecha_hora' => '2026-09-21 09:30:00', 'consulta_id' => 2],
        ]);

        DB::table('frotis_fsp')->insert([
            ['fsp_id' => 1, 'consulta_id' => 1, 'seg_val' => 48.0, 'lin_val' => 42.0, 'mon_val' => 7.0, 'eos_val' => 3.0, 'blastos_val' => 0.0, 'morfologia' => 'Eritrocitos con anisocitosis leve.', 'observaciones' => 'Sin blastos observados.'],
            ['fsp_id' => 2, 'consulta_id' => 2, 'seg_val' => 55.0, 'lin_val' => 35.0, 'mon_val' => 8.0, 'eos_val' => 2.0, 'blastos_val' => 0.0, 'morfologia' => 'Morfologia conservada.', 'observaciones' => 'Seguimiento estable.'],
        ]);

        DB::table('orden_laboratorio')->insert([
            ['orden_id' => 1, 'consulta_id' => 1, 'tipo_seguimiento' => 'Hemograma de control', 'fecha_solicitud' => '2026-08-20 08:45:00', 'status' => 'Pendiente'],
            ['orden_id' => 2, 'consulta_id' => 2, 'tipo_seguimiento' => 'Perfil hematologico', 'fecha_solicitud' => '2026-08-21 10:15:00', 'status' => 'Procesada'],
        ]);

        DB::table('resultado_laboratorio')->insert([
            ['resultado_id' => 1, 'orden_id' => 1, 'examen_id' => 1, 'valor_encontrado' => 10.8, 'status' => 'Activo'],
            ['resultado_id' => 2, 'orden_id' => 2, 'examen_id' => 2, 'valor_encontrado' => 220, 'status' => 'Activo'],
        ]);

        DB::table('protocolo_tratamiento')->insert([
            ['protocolo_id' => 1, 'paciente_id' => 1, 'consulta_id' => 1, 'nombre' => 'Control de anemia falciforme', 'indicaciones' => 'Hidratacion y seguimiento hematologico.', 'fecha_inicio' => '2026-08-20', 'estado' => 'Activo', 'usuario_medico_id' => 3],
            ['protocolo_id' => 2, 'paciente_id' => 2, 'consulta_id' => 2, 'nombre' => 'Protocolo de mantenimiento', 'indicaciones' => 'Continuar vigilancia clinica.', 'fecha_inicio' => '2026-08-21', 'estado' => 'Suspendido', 'usuario_medico_id' => 3],
        ]);

        DB::table('protocolo_ciclo')->insert([
            ['ciclo_id' => 1, 'protocolo_id' => 1, 'numero_ciclo' => 1, 'fecha_inicio' => '2026-08-20', 'estado' => 'En curso'],
            ['ciclo_id' => 2, 'protocolo_id' => 2, 'numero_ciclo' => 1, 'fecha_inicio' => '2026-08-21', 'estado' => 'Pendiente'],
        ]);

        DB::table('protocolo_sesion')->insert([
            ['sesion_id' => 1, 'ciclo_id' => 1, 'fecha_hora' => '2026-08-20 11:00:00', 'tipo_sesion' => 'Evaluacion', 'status' => 'Realizada'],
            ['sesion_id' => 2, 'ciclo_id' => 2, 'fecha_hora' => '2026-08-28 11:00:00', 'tipo_sesion' => 'Seguimiento', 'status' => 'Programada'],
        ]);

        DB::table('tipaje_sanguineo')->insert([
            ['tipaje_id' => 1, 'paciente_id' => 1, 'fecha' => '2026-08-20', 'grupo_ab' => 'O', 'factor_rh' => '+', 'fenotipo_extendido' => 'CcEe', 'genetica_transfusional' => 'Sin hallazgos relevantes.', 'metodo' => 'Serologia', 'usuario_registrador_id' => 3, 'status' => 'Activo'],
            ['tipaje_id' => 2, 'paciente_id' => 2, 'fecha' => '2026-08-21', 'grupo_ab' => 'A', 'factor_rh' => '+', 'fenotipo_extendido' => 'Ccee', 'genetica_transfusional' => 'Compatible.', 'metodo' => 'Serologia', 'usuario_registrador_id' => 3, 'status' => 'Activo'],
        ]);

        DB::table('unidad_hemocomponente')->insert([
            ['unidad_id' => 1, 'codigo_bolsa' => 'BOL-000001', 'tipo_componente' => 'Concentrado de hematies', 'grupo_rh' => 'O+', 'volumen_ml' => 280, 'fecha_vencimiento' => '2026-09-30', 'status' => 'Disponible'],
            ['unidad_id' => 2, 'codigo_bolsa' => 'BOL-000002', 'tipo_componente' => 'Plasma fresco congelado', 'grupo_rh' => 'A+', 'volumen_ml' => 250, 'fecha_vencimiento' => '2027-01-15', 'status' => 'Reservada'],
        ]);

        DB::table('transfusion')->insert([
            ['transfusion_id' => 1, 'paciente_id' => 1, 'unidad_id' => 1, 'consulta_id' => 1, 'fecha_hora' => '2026-08-20 14:00:00', 'volumen_adm' => 280, 'status' => 'Realizada'],
            ['transfusion_id' => 2, 'paciente_id' => 2, 'unidad_id' => 2, 'consulta_id' => 2, 'fecha_hora' => '2026-08-21 14:30:00', 'volumen_adm' => 250, 'status' => 'Programada'],
        ]);

        DB::table('auditoria')->insert([
            ['auditoria_id' => 1, 'descripcion' => 'Registro de paciente de prueba', 'modulo' => 'Pacientes', 'id_usuario' => 1, 'fecha_hora' => '2026-08-20 08:00:00', 'accion' => 'CREATE'],
            ['auditoria_id' => 2, 'descripcion' => 'Actualizacion de consulta', 'modulo' => 'Consultas', 'id_usuario' => 3, 'fecha_hora' => '2026-08-21 10:00:00', 'accion' => 'UPDATE'],
        ]);

    }
}
