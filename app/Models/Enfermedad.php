<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Enfermedad extends Model
{
    use HasFactory;

    protected $table = 'enfermedad';

    protected $fillable = ['codigo_cie10', 'nombre', 'descripcion', 'activa'];
}