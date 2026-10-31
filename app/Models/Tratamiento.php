<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tratamiento extends Model
{
    protected $table = 'logistica.tratamiento';
    protected $primaryKey = 'tratamiento_id';
    protected $fillable = ['status'];
    public $incrementing = true;
    public $timestamps = false;

    public function medicina(): HasMany
    {
        return $this->hasMany(MedicinaTrat::class, 'tratamiento_id', 'tratamiento_id');
    }
}