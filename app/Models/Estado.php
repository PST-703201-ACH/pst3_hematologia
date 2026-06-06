<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Estado extends Model
{
    use HasFactory;

    protected $table = 'estados';

    protected $fillable = ['nombre'];

    /**
     * RELACIÓN POO: Un Estado tiene muchos Municipios (Asociación 1 a Muchos)
     */
    public function municipios()
    {
        return $this->hasMany(Municipio::class, 'estado_id');
    }
}