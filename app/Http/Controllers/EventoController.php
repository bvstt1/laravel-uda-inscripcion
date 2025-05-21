<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Evento;

class EventoController extends Controller
{
    public function create()
    {
        $semanales = Evento::where('tipo', 'semanal')->get()->map(function ($evento) {
            $inicio = \Carbon\Carbon::parse($evento->fecha);
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

    public function fechasSemana($id)
    {
        $evento = Evento::findOrFail($id);
        $inicio = \Carbon\Carbon::parse($evento->fecha)->startOfWeek(); // lunes
        $fin = $inicio->copy()->addDays(6); // domingo

        // Simular días ocupados (deberías consultar en BD los eventos diarios de esa semana)
        $ocupados = Evento::where('id_evento_padre', $id)
            ->pluck('fecha')
            ->map(fn($f) => \Carbon\Carbon::parse($f)->toDateString());

        return response()->json([
            'inicio' => $inicio->toDateString(),
            'fin' => $fin->toDateString(),
            'ocupados' => $ocupados
        ]);
    }

}
