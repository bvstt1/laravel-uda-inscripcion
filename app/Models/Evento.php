<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Evento extends Model
{
    protected $fillable = [
        'tipo',
        'titulo',
        'fecha',
        'lugar',
        'hora',
        'hora_termino',
        'descripcion',
        'id_evento_padre',
        'categoria_id' // ← agrega esta línea
    ];


    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }

}
