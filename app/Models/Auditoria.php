<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Auditoria extends Model
{
    protected $table = 'permiso';
    public $timestamps = false; 
    protected $primaryKey = 'permiso_id';
    public $incrementing = true;
    protected $keyType = 'int';
    protected $fillable = ['descripcion', 'modulo', 'id_usuario', 'fecha_hora'];

    public function usuario(){
        return $this->belongsTo(User::class, 'id_usuario', 'usuario_id');
    }
}
