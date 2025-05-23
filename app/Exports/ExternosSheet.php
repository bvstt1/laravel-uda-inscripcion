<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ExternosSheet implements FromCollection, WithTitle, WithHeadings
{
    protected $eventoId;

    public function __construct($eventoId)
    {
        $this->eventoId = $eventoId;
    }

    public function collection()
    {
        return DB::table('inscripciones')
            ->join('externos', 'inscripciones.rut_usuario', '=', 'externos.rut')
            ->where('inscripciones.id_evento', $this->eventoId)
            ->where('inscripciones.tipo_usuario', 'externo')
            ->select('externos.rut', 'externos.correo', 'externos.institucion', 'externos.cargo', 'inscripciones.fecha_inscripcion')
            ->get();
    }

    public function title(): string
    {
        return 'Externos';
    }

    public function headings(): array
    {
        return ['RUT', 'Correo', 'Institución', 'Cargo', 'Fecha de Inscripción'];
    }
}
