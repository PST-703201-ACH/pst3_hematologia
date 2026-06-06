<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Persona extends Model
{
    use HasFactory;

    protected $table = 'personas';

    protected $fillable = [
        'cedula',
        'nombres',
        'apellidos',
        'fecha_nacimiento',
        'sexo',
        'telefono',
        'correo',
        'direccion_detalle',
        'parroquia_id'
    ];

    /**
     * RELACIÓN POO: Una Persona pertenece a una Parroquia
     */
    public function parroquia()
    {
        return $this->belongsTo(Parroquia::class, 'parroquia_id');
    }

    /**
     * RELACIÓN POO: Una Persona puede tener asignados varios Roles en el sistema
     */
    public function roles()
    {
        return $this->belongsToMany(Rol::class, 'usuarios_roles', 'persona_id', 'rol_id')
                    ->withTimestamps();
    }
}