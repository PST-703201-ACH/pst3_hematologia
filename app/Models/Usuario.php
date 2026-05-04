<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Usuario extends Model
{
    protected $table = 'usuario';
    public $timestamps = false; 
    protected $primaryKey = 'usuario_id';
    public $incrementing = true;
    protected $keyType = 'int';
    protected $fillable = ['username', 'persona_id', 'password_hash', 'status', 'id_rol'];

    public function persona(){
        return $this->belongsTo(Persona::class, 'persona_id', 'persona_id');
    }
    public function rol(){
    return $this->belongsTo(Rol::class, 'id_rol', 'rol_id');
    }
}
