<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReaccionAdversa extends Model
{
    protected $table = 'logistica.reaccion_adversa';
    protected $primaryKey = 'reaccion_adversa_id';
    protected $fillable = [
        'paciente_id',
        'sesion_id',
        'transfusion_id',
        'usuario_id',
        'categoria',
        'gravedad',
        'fecha_hora',
        'descripcion',
        'intervencion',
    ];
    public $timestamps = false;
}
