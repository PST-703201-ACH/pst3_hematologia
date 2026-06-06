<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Municipio extends Model
{
    use HasFactory;

    protected $table = 'municipios';

    protected $fillable = ['estado_id', 'nombre'];

    /**
     * RELACIÓN POO: Un Municipio pertenece a un Estado
     */
    public function estado()
    {
        return $this->belongsTo(Estado::class, 'estado_id');
    }

    /**
     * RELACIÓN POO: Un Municipio tiene muchas Parroquias
     */
    public function parroquias()
    {
        return $this->hasMany(Parroquia::class, 'municipio_id');
    }
}