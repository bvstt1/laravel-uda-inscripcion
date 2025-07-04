<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;

class EstudiantesSheet implements FromCollection, WithTitle, WithHeadings
{
    protected $eventoId;

    public function __construct($eventoId)
    {
        $this->eventoId = $eventoId;
    }

    public function collection()
    {
        return DB::table('inscripciones')
            ->join('estudiantes', 'inscripciones.rut_usuario', '=', 'estudiantes.rut')
            ->where('inscripciones.id_evento', $this->eventoId)
            ->where('inscripciones.tipo_usuario', 'estudiante')
            ->select(
                'estudiantes.rut',
                'estudiantes.correo',
                'estudiantes.carrera',
                'inscripciones.fecha_inscripcion',
                'inscripciones.asistio_at',
                'inscripciones.estado'
            )
            ->get()
            ->map(function ($registro) {
                return [
                    $registro->rut,
                    $registro->correo,
                    $registro->carrera,
                    $registro->fecha_inscripcion,
                    $registro->asistio_at ? 'Sí' : '',
                    $registro->estado === 'desinscrito' ? 'Desinscrito' : 'Inscrito',
                ];
            });
    }

    public function title(): string
    {
        return 'Estudiantes';
    }

    public function headings(): array
    {
        return ['RUT', 'Correo', 'Carrera', 'Fecha de Inscripción', 'Asistió', 'Estado'];
    }
}
