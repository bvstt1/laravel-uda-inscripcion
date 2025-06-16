<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Evento;
use App\Models\Inscripcion;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\InscripcionesPorEventoExport;
use Illuminate\Support\Str;
use App\Models\Categoria;
use Carbon\Carbon;

class AdminInscripcionController extends Controller
{
    public function verEventos(Request $request)
    {
        $categoriaId = $request->input('categoria_id');
        $estado = $request->input('estado'); // 'activo', 'pasado', o null

        $queryDiarios = Evento::where('tipo', 'diario')->whereNull('id_evento_padre');
        $querySemanales = Evento::where('tipo', 'semanal');

        if ($categoriaId) {
            $queryDiarios->where('categoria_id', $categoriaId);
            $querySemanales->where('categoria_id', $categoriaId);
        }

        if ($estado === 'activo') {
            $queryDiarios->where('fecha', '>=', Carbon::today());
            $querySemanales->where('fecha', '>=', Carbon::today());
        } elseif ($estado === 'pasado') {
            $queryDiarios->where('fecha', '<', Carbon::today());
            $querySemanales->where('fecha', '<', Carbon::today());
        }

        $eventosDiariosAislados = $queryDiarios->orderBy('fecha')->get();
        $eventosSemanales = $querySemanales->orderBy('fecha')->get();
        $categorias = Categoria::orderBy('nombre')->get();

        return view('admin.verInscripciones', compact('eventosDiariosAislados', 'eventosSemanales', 'categorias'));
    }
    public function verDiasEventoSemanal($id)
    {
        \Carbon\Carbon::setLocale('es');

        $eventoSemanal = Evento::findOrFail($id);

        $eventosDiarios = Evento::where('id_evento_padre', $id)
            ->where('tipo', 'diario')
            ->orderBy('fecha')
            ->get();

        return view('admin.verDiasInscripciones', compact('eventoSemanal', 'eventosDiarios'));
    }
    public function verInscritosPorEvento($id)
    {
        $evento = Evento::findOrFail($id);
    
        $inscripcionesEstudiantes = Inscripcion::where('id_evento', $id)
            ->where('tipo_usuario', 'estudiante')
            ->get();
    
        $inscripcionesExternos = Inscripcion::where('id_evento', $id)
            ->where('tipo_usuario', 'externo')
            ->get();
    
        return view('admin.verInscritosEvento', compact('evento', 'inscripcionesEstudiantes', 'inscripcionesExternos'));
    }
    public function exportarExcel($id)
    {
        $evento = Evento::findOrFail($id);
        $titulo = Str::slug($evento->titulo, '_'); // convierte a formato archivo
    
        $nombreArchivo = 'inscritos_' . $titulo . '.xlsx';

        return Excel::download(new InscripcionesPorEventoExport($id), $nombreArchivo);
    }
}
