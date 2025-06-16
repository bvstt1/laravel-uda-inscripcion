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

    public static function inscripcionesUsuario($rut)
    {
        $inscripciones = \App\Models\Inscripcion::where('rut_usuario', $rut)->get();
        $resultado = [];

        foreach ($inscripciones as $ins) {
            $resultado[$ins->id_evento] = $ins;
        }

        return $resultado;
    }

    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }

}
