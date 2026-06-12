<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Municipio extends Model
{
    protected $table = 'municipio';
    protected $primaryKey = 'municipio_id';
    protected $fillable = ['nombre'];
    public $incrementing = true;
    public $timestamps = false;

}
