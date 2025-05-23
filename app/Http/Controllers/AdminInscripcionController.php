<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Evento;
use App\Models\Inscripcion;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\InscripcionesPorEventoExport;
use Illuminate\Support\Str;


class AdminInscripcionController extends Controller
{
    public function verEventos()
    {
        $eventosDiariosAislados = Evento::where('tipo', 'diario')
            ->whereNull('id_evento_padre')
            ->orderBy('titulo')
            ->get();

        $eventosSemanales = Evento::where('tipo', 'semanal')
            ->orderBy('titulo')
            ->get();

        return view('admin.verInscripciones', compact('eventosDiariosAislados', 'eventosSemanales'));
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
