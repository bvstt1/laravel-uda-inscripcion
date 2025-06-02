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
                ->select(
                    'externos.rut',
                    'externos.correo',
                    'externos.institucion',
                    'externos.cargo',
                    'inscripciones.fecha_inscripcion',
                    'inscripciones.asistio_at'
                )
                ->get()
                ->map(function ($registro) {
                    return [
                        $registro->rut,
                        $registro->correo,
                        $registro->institucion,
                        $registro->cargo,
                        $registro->fecha_inscripcion,
                        $registro->asistio_at ? 'Sí' : '',
                    ];
                });
        }
        

        public function title(): string
        {
            return 'Externos';
        }

        public function headings(): array
        {
            return ['RUT', 'Correo', 'Institución', 'Cargo', 'Fecha de Inscripción', 'Asistió'];
        }
    }
