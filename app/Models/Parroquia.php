<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Parroquia extends Model
{
    use HasFactory;

    protected $table = 'parroquias';

    protected $fillable = ['municipio_id', 'nombre'];

    /**
     * RELACIÓN POO: Una Parroquia pertenece a un Municipio
     */
    public function municipio()
    {
        return $this->belongsTo(Municipio::class, 'municipio_id');
    }

    /**
     * RELACIÓN POO: Una Parroquia puede tener muchas Personas viviendo en ella
     */
    public function personas()
    {
        return $this->hasMany(Persona::class, 'parroquia_id');
    }
}