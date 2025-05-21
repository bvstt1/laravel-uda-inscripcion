<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Evento extends Model
{
    protected $fillable = [
        'tipo', 'titulo', 'fecha', 'lugar', 'hora', 'hora_termino', 'descripcion', 'id_evento_padre'
    ];
}
