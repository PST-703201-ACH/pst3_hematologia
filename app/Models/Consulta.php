<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Consulta extends Model
{
    protected $table = 'consulta';
    protected $primaryKey = 'consulta_id';
    protected $fillable = ['paciente_id', 'medico_id', 'tipo_id', 'enfermedad_id', 'fecha_hora', 'peso', 'talla', 'sc', 'fc', 'fr', 'subjetivo', 'plan_trabajo', 'proxima_cita', 'status'];
    public $incrementing = true;
    public $timestamps = false;

    public function medico()
    {
        return $this->belongsTo(Usuario::class, 'medico_id', 'persona_id');
    }

    public function paciente()
    {
        return $this->belongsTo(Paciente::class, 'paciente_id', 'paciente_id');
    }

    public function enfermedad()
    {
        return $this->belongsTo(Enfermedad::class, 'enfermedad_id', 'enfermedad_id');
    }
}