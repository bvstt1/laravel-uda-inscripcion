<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Estudiante extends Authenticatable
{
    use HasFactory;

    protected $fillable = ['rut', 'nombre', 'apellido',
        'rut','correo', 'carrera', 'contrasena'];

    public function getAuthPassword()
    {
        return $this->contrasena;
    }
}
