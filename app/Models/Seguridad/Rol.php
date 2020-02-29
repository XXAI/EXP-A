<?php

namespace App\Models\Seguridad;

use Illuminate\Database\Eloquent\Model;

class Rol extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'roles';

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
        return $this->belongsToMany('App\Models\Seguridad\Permiso','permiso_rol','rol_id','permiso_id');
    }
}