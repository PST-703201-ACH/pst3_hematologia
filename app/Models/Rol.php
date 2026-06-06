<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rol extends Model
{
    use HasFactory;

    protected $table = 'roles';

    protected $fillable = ['nombre', 'descripcion'];

    /**
     * RELACIÓN POO: Un Rol puede pertenecer a muchas Personas (Muchos a Muchos)
     */
    public function personas()
    {
        return $this->belongsToMany(Persona::class, 'usuarios_roles', 'rol_id', 'persona_id')
                    ->withTimestamps();
    }
}