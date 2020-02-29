<?php

namespace App\Models\Seguridad;

use Illuminate\Database\Eloquent\Model;

class GrupoPermiso extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'grupo_permisos';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nombre'
    ];
}