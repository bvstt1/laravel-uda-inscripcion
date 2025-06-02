<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Evento;
use Carbon\Carbon;
use App\Models\Inscripcion;

class EventoController extends Controller
{
    public function create()
    {
        $semanales = Evento::where('tipo', 'semanal')->get()->map(function ($evento) {
            $inicio = Carbon::parse($evento->fecha);
            $fechasSemana = collect();
            for ($i = 0; $i < 7; $i++) {
                $fechasSemana->push($inicio->copy()->addDays($i)->toDateString());
            }
            $evento->fechas = $fechasSemana;
            return $evento;
        });

        return view('admin.crearEvento', compact('semanales'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tipo' => 'required|in:semanal,diario',
            'titulo' => 'required|string',
            'fecha' => 'required|date',
            'lugar' => 'required|string',
            'descripcion' => 'required|string',
            'id_evento_padre' => 'nullable|exists:eventos,id',
            'hora' => 'nullable|required_if:tipo,diario|date_format:H:i',
            'hora_termino' => 'nullable|required_if:tipo,diario|date_format:H:i|after:hora',
        ]);

        Evento::create($request->all());

        return redirect()->back()->with('success', 'Evento creado correctamente.');
    }

    public function index()
    {
        $eventosSemanales = Evento::where('tipo', 'semanal')->get();
        $eventosDiarios = Evento::where('tipo', 'diario')->whereNull('id_evento_padre')->get();

        return view('admin.administrarEvento', compact('eventosDiarios', 'eventosSemanales'));
    }

    public function edit($id)
    {
        $evento = Evento::findOrFail($id);
        $semanales = Evento::where('tipo', 'semanal')->get();

        return view('admin.editarEvento', compact('evento', 'semanales'));
    }

    public function update(Request $request, $id)
    {
        $evento = Evento::findOrFail($id);
        $tipoAnterior = $evento->tipo;
    
        $request->validate([
            'tipo' => 'required|in:semanal,diario',
            'titulo' => 'required|string',
            'fecha' => 'required|date',
            'lugar' => 'required|string',
            'descripcion' => 'required|string',
            'id_evento_padre' => 'nullable|exists:eventos,id',
            'hora' => 'nullable|required_if:tipo,diario|date_format:H:i',
            'hora_termino' => 'nullable|required_if:tipo,diario|date_format:H:i|after:hora',
        ]);
    
        // Si pasa de semanal a diario → eliminar eventos asociados
        if ($tipoAnterior === 'semanal' && $request->tipo === 'diario') {
            Evento::where('id_evento_padre', $id)->delete();
        }
    
        $evento->update($request->all());
    
        return redirect()->route('eventos.index')->with('success', 'Evento actualizado correctamente.');
    }
    

    public function destroy($id)
    {
        $evento = Evento::findOrFail($id);

        if ($evento->tipo === 'semanal') {
            Evento::where('id_evento_padre', $id)->delete();
        }

        $evento->delete();

        return redirect()->route('eventos.index')->with('success', 'Evento eliminado correctamente.');
    }
    public function fechasSemana($id)
    {
        $evento = Evento::findOrFail($id);

        $inicio = Carbon::parse($evento->fecha)->startOfWeek();
        $fin = Carbon::parse($evento->fecha)->endOfWeek();

        $ocupados = Evento::where('id_evento_padre', $id)
            ->pluck('fecha')
            ->map(fn($f) => Carbon::parse($f)->format('Y-m-d'));

        return response()->json([
            'inicio' => $inicio->format('Y-m-d'),
            'fin' => $fin->format('Y-m-d'),
            'ocupados' => $ocupados,
        ]);
    }

    public function verDias($id)
    {
        $eventoSemanal = Evento::where('tipo', 'semanal')->findOrFail($id);
        $eventosDiarios = Evento::where('id_evento_padre', $id)->orderBy('fecha')->get();

        return view('admin.diasAsociados', compact('eventoSemanal', 'eventosDiarios'));
    }

    public function mostrarEventosUsuarios()
    {
        $rut = session('rut'); // o auth()->user()->rut si estás usando Auth
        $eventosDiarios = Evento::where('tipo', 'diario')->whereNull('id_evento_padre')->get();
        $eventosSemanales = Evento::where('tipo', 'semanal')->get();
    
        $inscripciones = Inscripcion::where('rut_usuario', $rut)->get()->keyBy('id_evento');
    
        return view('user.inscripcionEventos', compact('eventosDiarios', 'eventosSemanales', 'inscripciones'));
    }
    public function verDiasUsuario($id)
    {
        $eventoSemanal = Evento::findOrFail($id);
        $eventosDiarios = Evento::where('id_evento_padre', $id)->orderBy('fecha')->get();
    
        $rut = session('rut');
        $inscritos = Inscripcion::where('rut_usuario', $rut)->pluck('id_evento')->toArray();
    
        return view('user.verDiasEvento', compact('eventoSemanal', 'eventosDiarios', 'inscritos'));
    }

    
}
