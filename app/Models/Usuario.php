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
    protected $fillable = ['username', 'persona_id', 'password_hash'];
}
