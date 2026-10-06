<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fase extends Model
{
    protected $table = 'logistica.fase';
    protected $primaryKey = 'fase_id';
    protected $fillable = ['numero'];
    public $incrementing = true;
    public $timestamps = false;
}
