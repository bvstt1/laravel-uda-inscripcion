<?php

namespace App\Exports;

use App\Models\AsistenciaLibre;
use App\Models\Estudiante;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AsistenciaLibreExport implements FromCollection, WithHeadings
{
    protected $filtro;
    protected $fecha;

    public function __construct($filtro, $fecha)
    {
        $this->filtro = $filtro;
        $this->fecha = Carbon::parse($fecha);
    }

    public function collection()
    {
        $query = AsistenciaLibre::query();

        if ($this->filtro === 'dia') {
            $query->whereDate('created_at', $this->fecha);
        } elseif ($this->filtro === 'semana') {
            $query->whereBetween('created_at', [
                $this->fecha->copy()->startOfWeek(),
                $this->fecha->copy()->endOfWeek(),
            ]);
        } elseif ($this->filtro === 'mes') {
            $query->whereYear('created_at', $this->fecha->year)
                  ->whereMonth('created_at', $this->fecha->month);
        }

        $asistencias = $query->orderBy('created_at', 'desc')->get();

        return $asistencias->map(function ($asistencia) {
            $estudiante = Estudiante::where('rut', $asistencia->rut)->first();

            return [
                'RUT' => $asistencia->rut,
                'Correo' => $estudiante->correo ?? '-',
                'Carrera' => $estudiante->carrera ?? '-',
                'Fecha Registro' => $asistencia->created_at->format('Y-m-d H:i'),
            ];
        });
    }

    public function headings(): array
    {
        return [
            'RUT',
            'Correo',
            'Carrera',
            'Fecha Registro'
        ];
    }
}
