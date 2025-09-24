<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Externo extends Authenticatable
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'apellido',
        'rut',
        'correo',
        'institucion',
        'cargo',
        'contrasena',
    ];

    public function getAuthPassword()
    {
        return $this->contrasena;
    }
}
