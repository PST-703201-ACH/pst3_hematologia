<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MedicinaProt extends Model
{
    protected $table = 'logistica.medicina_pro';
    protected $primaryKey = 'id';
    protected $fillable = ['id_protocolo', 'id_medicina', 'id_fase'];
    public $incrementing = true;
    public $timestamps = false;

    public function protocolo()
    {
        return $this->belongsTo(Protocolo::class, 'id_protocolo', 'protocolo_id');
    }

    public function medicina()
    {
        return $this->belongsTo(Medicina::class, 'id_medicina', 'medicina_id');
    }

    public function fase()
    {
        return $this->belongsTo(Fase::class, 'id_fase', 'fase_id');
    }
}
