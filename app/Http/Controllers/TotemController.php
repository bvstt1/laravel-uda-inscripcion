<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Inscripcion;
use App\Models\Evento;
use Illuminate\Support\Carbon;

class TotemController extends Controller
{
    public function form($id)
    {
        $evento = Evento::findOrFail($id);

        $terminaEn = Carbon::parse($evento->fecha . ' ' . $evento->hora_termino)
            ->subMinutes(15);

        if (now()->greaterThanOrEqualTo($terminaEn)) {
            return view('totem.bloqueado', compact('evento'));
        }

        return view('totem.form', compact('evento'));
    }

    public function registrarAsistencia(Request $request, $id)
    {
        $evento = Evento::findOrFail($id);

        $terminaEn = Carbon::parse($evento->fecha . ' ' . $evento->hora_termino)
            ->subMinutes(15);

        if (now()->greaterThanOrEqualTo($terminaEn)) {
            return back()->with('error', 'El registro de asistencia ha sido cerrado para este evento.');
        }

        $rut = $request->input('rut');

        $inscripcion = Inscripcion::where('rut_usuario', $rut)
            ->where('id_evento', $evento->id)
            ->where('estado', 'inscrito')
            ->whereNull('asistio_at')
            ->first();

        if (!$inscripcion) {
            return back()->with('error', 'No se encontró una inscripción activa o ya registraste tu asistencia.');
        }

        $inscripcion->asistio_at = now();
        $inscripcion->save();

        return back()->with('success', '¡Asistencia registrada exitosamente!');
    }
    public function seleccionarEvento()
    {
        $eventos = Evento::where('tipo', 'diario')
            ->whereNotNull('hora_termino')
            ->orderBy('fecha')
            ->orderBy('hora')
            ->get();

        return view('totem.selector', compact('eventos'));
    }

}
