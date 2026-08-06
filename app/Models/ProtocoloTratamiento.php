<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProtocoloTratamiento extends Model
{
  public function medicamentos()
{
    return $this->belongsToMany(Medicamento::class, 'medicamento_protocolo_sesion', 'sesion_id', 'medicamento_id')
                ->withPivot('dosis_indicada', 'via_administracion', 'observaciones_aplicacion')
                ->withTimestamps();
}  //
}
