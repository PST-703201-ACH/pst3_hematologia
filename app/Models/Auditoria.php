<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Auditoria extends Model
{
    protected $table = 'auditoria';
    public $timestamps = false; 
    protected $primaryKey = 'auditoria_id';
    public $incrementing = true;
    protected $keyType = 'int';
    protected $fillable = ['descripcion', 'modulo', 'fecha_hora', 'id_usuario', 'accion'];

    public function usuario(){
        return $this->belongsTo(User::class, 'id_usuario', 'usuario_id');
    }
}
