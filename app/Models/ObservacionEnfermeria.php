<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ObservacionEnfermeria extends Model
{
    protected $table = 'logistica.observacion_enfermeria';
    protected $primaryKey = 'observacion_id';
    protected $fillable = [
        'sala_id',
        'paciente_id',
        'sesion_id',
        'usuario_id',
        'categoria',
        'gravedad',
        'fecha_hora',
        'descripcion',
        'accion_tomada',
    ];
    public $timestamps = false;
}
