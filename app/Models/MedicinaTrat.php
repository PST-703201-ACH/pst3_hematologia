<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MedicinaTrat extends Model
{
    protected $table = 'logistica.medicina_trat';
    protected $primaryKey = 'id';
    protected $fillable = ['paciente_id', 'consulta_id', 'protocolo_id', 'medico_id', 'medicina_id', 'tratamiento_id', 'via_admin', 'status'];
    public $incrementing = true;
    public $timestamps = false;

    public function paciente()
    {
        return $this->belongsTo(Paciente::class, 'paciente_id', 'paciente_id');
    }

    public function consulta()
    {
        return $this->belongsTo(Consulta::class, 'consulta_id', 'consulta_id');
    }

    public function protocolo()
    {
        return $this->belongsTo(Protocolo::class, 'protocolo_id', 'protocolo_id');
    }

    public function medico()
    {
        return $this->belongsTo(User::class, 'usuario_id', 'usuario_id');
    }

    public function medicina()
    {
        return $this->belongsTo(Medicina::class, 'medicina_id', 'medicina_id');
    }

    public function tratamiento()
    {
        return $this->belongsTo(Tratamiento::class, 'tratamiento_id', 'tratamiento_id');
    }
}