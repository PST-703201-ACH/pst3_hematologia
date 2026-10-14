<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProtocoloTratamiento extends Model
{
    protected $table = 'logistica.protocolo_tratamiento';
    protected $primaryKey = 'protocolo_tratamiento_id';
    protected $fillable = [
        'paciente_id',
        'consulta_id',
        'protocolo_id',
        'usuario_medico_id',
        'fecha_inicio',
        'estado',
        'indicaciones',
    ];
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

    public function ciclos()
    {
        return $this->hasMany(ProtocoloCiclo::class, 'protocolo_tratamiento_id', 'protocolo_tratamiento_id');
    }
}
