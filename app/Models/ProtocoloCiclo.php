<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProtocoloCiclo extends Model
{
    protected $table = 'logistica.protocolo_ciclo';
    protected $primaryKey = 'ciclo_id';
    protected $fillable = [
        'protocolo_tratamiento_id',
        'numero_ciclo',
        'fecha_inicio',
        'fecha_fin',
        'estado',
    ];
    public $timestamps = false;

    public function tratamiento()
    {
        return $this->belongsTo(ProtocoloTratamiento::class, 'protocolo_tratamiento_id', 'protocolo_tratamiento_id');
    }

    public function sesiones()
    {
        return $this->hasMany(ProtocoloSesion::class, 'ciclo_id', 'ciclo_id');
    }
}
