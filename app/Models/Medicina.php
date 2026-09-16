<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Medicina extends Model
{
    protected $table = 'medicina';
    protected $primaryKey = 'medicina_id';
    protected $fillable = ['descripcion'];
    public $incrementing = true;
    public $timestamps = false;
}