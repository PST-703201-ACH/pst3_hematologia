<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Paciente extends Model
{
    protected $table = 'paciente';
    protected $primaryKey = 'paciente_id';
    public $timestamps = false;
    protected $fillable = ['paciente_id', 'persona_id', 'hc', 'status'];

    public function persona()
    {
        return $this->belongsTo(Persona::class, 'persona_id', 'persona_id');
    }
}
