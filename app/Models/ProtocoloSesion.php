<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProtocoloSesion extends Model
{
    protected $table = 'logistica.protocolo_sesion';
    protected $primaryKey = 'sesion_id';
    protected $fillable = [
        'ciclo_id',
        'medicina_pro_id',
        'sala_id',
        'fecha_hora',
        'tipo_sesion',
        'dosis',
        'via_administracion',
        'status',
        'fecha_hora_aplicacion',
        'usuario_enfermero_id',
        'identidad_confirmada',
        'observaciones_enfermeria',
    ];
    public $timestamps = false;

    protected function casts(): array
    {
        return ['identidad_confirmada' => 'boolean'];
    }

    public function ciclo()
    {
        return $this->belongsTo(ProtocoloCiclo::class, 'ciclo_id', 'ciclo_id');
    }

    public function sala()
    {
        return $this->belongsTo(Sala::class, 'sala_id', 'sala_id');
    }
}
