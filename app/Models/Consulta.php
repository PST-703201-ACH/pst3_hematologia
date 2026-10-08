<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Consulta extends Model
{
    protected $table = 'logistica.consulta';
    protected $primaryKey = 'consulta_id';
    protected $fillable = [
        'paciente_id',
        'medico_id',
        'tipo',
        'enfermedad_id',
        'fecha_hora',
        'peso',
        'talla',
        'sc',
        'fc',
        'fr',
        'antecedentes_personales',
        'antecedentes_familiares',
        'signos_sintomas_iniciales',
        'subjetivo',
        'plan_trabajo',
        'proxima_cita',
        'status',
    ];
    public $incrementing = true;
    public $timestamps = false;

    public function medico()
    {
        return $this->belongsTo(User::class, 'medico_id', 'usuario_id');
    }

    public function paciente()
    {
        return $this->belongsTo(Paciente::class, 'paciente_id', 'paciente_id');
    }

    public function enfermedad()
    {
        return $this->belongsTo(Enfermedad::class, 'enfermedad_id', 'enfermedad_id');
    }

    public function cita()
    {
        return $this->hasOne(Cita::class, 'consulta_id', 'consulta_id');
    }
}