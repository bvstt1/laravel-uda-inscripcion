<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Inscripcion;
use Carbon\Carbon;
use App\Models\Evento;
use App\Mail\ConfirmacionEventoMail;
use Illuminate\Support\Facades\Mail;

class InscripcionController extends Controller
{
    public function store(Request $request, $id)
    {
        $rut = session('rut');
        $tipo = session('tipo_usuario');
    
        // Buscar inscripción previa activa
        $inscripcionExistente = Inscripcion::where('rut_usuario', $rut)
            ->where('id_evento', $id)
            ->where('estado', 'inscrito')
            ->first();
    
        if ($inscripcionExistente) {
            return back()->with('success', 'Ya estás inscrito en este evento.');
        }
    
        // Crear o reactivar inscripción (si estaba desinscrito antes)
        $inscripcion = Inscripcion::updateOrCreate(
            ['rut_usuario' => $rut, 'id_evento' => $id],
            [
                'tipo_usuario' => $tipo,
                'fecha_inscripcion' => now(),
                'estado' => 'inscrito',
            ]
        );
    
        // Obtener datos del evento
        $evento = Evento::findOrFail($id);
    
        // Buscar usuario
        $usuario = $tipo === 'estudiante'
            ? \App\Models\Estudiante::where('rut', $rut)->first()
            : \App\Models\Externo::where('rut', $rut)->first();
    
        // Enviar correo si tiene correo registrado
        $correoDestino = $usuario->correo ?? $usuario->email ?? null;
        if ($usuario && $correoDestino) {
            Mail::to($correoDestino)->send(new \App\Mail\ConfirmacionEventoMail(
                $usuario->nombre ?? 'Usuario',
                $evento->titulo,
                $evento->fecha . ' ' . ($evento->hora ?? ''),
                $evento->lugar,
                $rut,
                $id
            ));
        }
        return back()->with('success', 'Inscripción realizada correctamente.');
    }

    public function destroy($id)
    {
        $rut = session('rut');
    
        $inscripcion = Inscripcion::where('rut_usuario', $rut)
            ->where('id_evento', $id)
            ->first();
    
        if ($inscripcion && $inscripcion->estado === 'inscrito') {
            // Cambiar el estado a desinscrito (no se elimina)
            $inscripcion->estado = 'desinscrito';
            $inscripcion->save();
    
            // Eliminar el QR asociado
            $qrPath = storage_path('app/public/qr/qr_' . $rut . '_' . $id . '.svg');
            if (file_exists($qrPath)) {
                unlink($qrPath);
            }
    
            return back()->with('success', 'Desinscripción realizada correctamente.');
        }
    
        return back()->with('success', 'No estabas inscrito en este evento.');
    }
    

}
