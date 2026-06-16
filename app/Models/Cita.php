<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cita extends Model
{
    protected $table = 'cita';
    protected $primaryKey = 'cita_id';
    protected $fillable = ['nombres_paciente', 'apellidos_paciente', 'nombres_representante', 'apellidos_representante', 'numero_hc', 'fecha_hora', 'estatus', 'consulta_id'];
    public $incrementing = true;
    public $timestamps = false;

    public function consulta()
    {
        return $this->belongsTo(Consulta::class, 'consulta_id', 'consulta_id');
    }
}
