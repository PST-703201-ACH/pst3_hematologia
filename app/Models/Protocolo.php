<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Protocolo extends Model
{
    protected $table = 'protocolo';
    protected $primaryKey = 'protocolo_id';
    protected $fillable = ['nombre'];
    public $incrementing = true;
    public $timestamps = false;
}
