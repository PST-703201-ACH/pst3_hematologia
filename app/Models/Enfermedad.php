<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Enfermedad extends Model
{
    protected $table = 'enfermedad';
    protected $primaryKey = 'enfermedad_id';
    protected $fillable = ['tipo', 'descripcion', 'status'];
    public $incrementing = true;
    public $timestamps = false;
}
