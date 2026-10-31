<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Observacion extends Model
{
    protected $table = 'logistica.observacion';
    protected $primaryKey = 'observacion_id';
    protected $fillable = [
        'paciente_id',
        'enfermero_id',
        'tratamiento_id',
        'gravedad',
        'fecha_hora',
        'descripcion',
        'acciones',
    ];
    public $incrementing = true;
    public $timestamps = false;

    public function enfermero()
    {
        return $this->belongsTo(User::class, 'enfermero_id', 'usuario_id');
    }

    public function paciente()
    {
        return $this->belongsTo(Paciente::class, 'paciente_id', 'paciente_id');
    }

    public function tratamiento()
    {
        return $this->belongsTo(Tratamiento::class, 'tratamiento_id', 'tratamiento_id');
    }
}
