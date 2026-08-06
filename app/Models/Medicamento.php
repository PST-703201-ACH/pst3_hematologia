<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Medicamento extends Model
{
 public function sesiones()
{
    return $this->belongsToMany(ProtocoloSesion::class, 'medicamento_protocolo_sesion', 'medicamento_id', 'sesion_id')
                ->withPivot('dosis_indicada', 'via_administracion', 'observaciones_aplicacion')
                ->withTimestamps();
}   //
}
