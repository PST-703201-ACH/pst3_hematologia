<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sala extends Model
{
    protected $table = 'logistica.sala';
    protected $primaryKey = 'sala_id';
    protected $fillable = ['nombre', 'activa'];
    public $timestamps = false;

    protected function casts(): array
    {
        return ['activa' => 'boolean'];
    }
}
