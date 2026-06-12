<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rol extends Model
{
    protected $table = 'rol';
    protected $primaryKey = 'rol_id';
    protected $fillable = ['nombre', 'status'];
    public $incrementing = true;
    public $timestamps = false;
}
