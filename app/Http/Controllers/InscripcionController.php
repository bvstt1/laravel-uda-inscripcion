<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Inscripcion;
use Carbon\Carbon;

class InscripcionController extends Controller
{
    public function store(Request $request, $id)
    {
        // Validar si ya está inscrito
        $yaInscrito = Inscripcion::where('rut_usuario', session('rut'))
            ->where('id_evento', $id)
            ->exists();

        if ($yaInscrito) {
            return back()->with('success', 'Ya estás inscrito en este evento.');
        }

        // Crear la inscripción
        Inscripcion::create([
            'rut_usuario' => session('rut'), // o auth()->user()->rut si usas login con Auth
            'id_evento' => $id,
            'tipo_usuario' => session('tipo_usuario'), // o auth()->user()->tipo
            'fecha_inscripcion' => Carbon::now(),
        ]);

        return back()->with('success', 'Inscripción realizada correctamente.');
    }
    public function destroy($id)
    {
        $inscripcion = Inscripcion::where('rut_usuario', session('rut'))
            ->where('id_evento', $id)
            ->first();

        if ($inscripcion) {
            $inscripcion->delete();
            return back()->with('success', 'Desinscripción realizada correctamente.');
        }

        return back()->with('success', 'No estabas inscrito en este evento.');
    }

}
