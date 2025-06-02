<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AsistenciaLibre;
use App\Models\Estudiante;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AsistenciaLibreExport;
use Carbon\Carbon;

class AdminAsistenciaController extends Controller
{
    public function mostrarFormulario()
    {
        return view('admin.verAsistencias');
    }

    public function filtrarAsistencias(Request $request)
    {
        $request->validate([
            'filtro' => 'required|in:dia,semana,mes',
            'fecha' => 'required|date'
        ]);

        $fecha = Carbon::parse($request->fecha);
        $query = AsistenciaLibre::query();

        // Aplicar filtros según tipo
        if ($request->filtro === 'dia') {
            $query->whereDate('created_at', $fecha);
        } elseif ($request->filtro === 'semana') {
            $query->whereBetween('created_at', [
                $fecha->copy()->startOfWeek(),
                $fecha->copy()->endOfWeek(),
            ]);
        } elseif ($request->filtro === 'mes') {
            $query->whereYear('created_at', $fecha->year)
                  ->whereMonth('created_at', $fecha->month);
        }

        $asistencias = $query->orderBy('created_at', 'desc')->get();

        // Obtener datos de los estudiantes relacionados
        $asistenciasConEstudiante = $asistencias->map(function ($asistencia) {
            $estudiante = Estudiante::where('rut', $asistencia->rut)->first();
            return [
                'rut' => $asistencia->rut,
                'correo' => $estudiante->correo ?? '-',
                'carrera' => $estudiante->carrera ?? '-',
                'fecha' => $asistencia->created_at->format('Y-m-d H:i')
            ];
        });

        return view('admin.verAsistencias', [
            'asistencias' => $asistenciasConEstudiante,
            'filtro' => $request->filtro,
            'fecha' => $request->fecha
        ]);
    }

    public function exportarExcel(Request $request)
    {
        $request->validate([
            'filtro' => 'required|in:dia,semana,mes',
            'fecha' => 'required|date'
        ]);

        return Excel::download(new AsistenciaLibreExport($request->filtro, $request->fecha), 'asistencia_libre.xlsx');
    }
}
