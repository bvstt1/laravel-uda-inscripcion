<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inscripcion extends Model
{
    use HasFactory;

    protected $table = 'inscripciones';

    public $timestamps = false; // porque no usas created_at / updated_at

    protected $fillable = [
        'rut_usuario',
        'id_evento',
        'tipo_usuario',
        'fecha_inscripcion',
    ];

    // Relación con Evento (opcional pero útil)
    public function evento()
    {
        return $this->belongsTo(Evento::class, 'id_evento');
    }
}
