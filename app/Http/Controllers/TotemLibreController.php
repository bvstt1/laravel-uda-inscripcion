<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AsistenciaLibre;
use Carbon\Carbon;
use App\Models\Estudiante;
use App\Models\Externo;


class TotemLibreController extends Controller
{
    public function index()
    {
        return view('totem.asistenciaLibre');
    }

    public function registrar(Request $request)
    {
        $request->validate([
            'rut' => 'required|string|max:12',
        ]);
    
        $rut = strtoupper($request->input('rut'));
        $fecha = \Carbon\Carbon::now()->toDateString();
    
        // ✅ Verificar existencia del RUT en estudiantes o externos
        $usuarioExiste = Estudiante::where('rut', $rut)->exists() || Externo::where('rut', $rut)->exists();
    
        if (!$usuarioExiste) {
            return redirect()->back()->with('error', 'El RUT ingresado no está registrado en el sistema.');
        }
    
        // ✅ Evitar duplicado del día
        $yaRegistrado = \App\Models\AsistenciaLibre::where('rut', $rut)
                            ->whereDate('created_at', $fecha)
                            ->exists();
    
        if ($yaRegistrado) {
            return redirect()->back()->with('error', 'Ya registraste asistencia hoy.');
        }
    
        \App\Models\AsistenciaLibre::create([
            'rut' => $rut,
        ]);
    
        return redirect()->back()->with('success', '¡Asistencia registrada exitosamente!');
    }
}
