<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Persona extends Model
{
    protected $table = 'persona';
    public $timestamps = false; 
    protected $primaryKey = 'persona_id';
    public $incrementing = true;
    protected $keyType = 'int';
    protected $fillable = ['nombres', 'apellidos', 'fecha_nacimiento', 'sexo', 'cedula', 'telefono', 'email', 'estado_id', 'municipio_id', 'parroquia_id', 'direccion_exacta'];

    public function estado(){
    return $this->belongsTo(Estado::class, 'estado_id', 'estado_id');
    }

    public function municipio(){
    return $this->belongsTo(Municipio::class, 'municipio_id', 'municipio_id');
    }

    public function parroquia(){
    return $this->belongsTo(Parroquia::class, 'parroquia_id', 'parroquia_id');
    }
}
