<?php

namespace App\Models\Seguridad;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GrupoPermiso extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'grupo_permisos';

    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nombre'
    ];

    /**
     * Get all of the posts for the country.
     */
    public function permisos()
    {
        return $this->hasMany('App\Models\Seguridad\Permiso','grupo_id');
    }
}