<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Evento;
use App\Models\Inscripcion;
use App\Models\Categoria;
use App\Mail\NotificacionCambioEventoMail;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

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

        $categorias = Categoria::all();

        return view('admin.crearEvento', compact('semanales', 'categorias'));
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
            'categoria_id' => 'nullable|exists:categorias,id',
        ]);

        $datos = $request->all();
        $datos['categoria_id'] = $request->input('categoria_id') ?: 1;

        Evento::create($datos);

        return redirect()->back()->with('success', 'Evento creado correctamente.');
    }

    public function index(Request $request)
    {
        $categoriaId = $request->categoria_id;

        $eventos = Evento::when($categoriaId, function ($query) use ($categoriaId) {
            $query->where('categoria_id', $categoriaId);
        })->get();

        $eventosDiarios = $eventos->where('tipo', 'diario')->whereNull('id_evento_padre');
        $eventosSemanales = $eventos->where('tipo', 'semanal');
        $categorias = Categoria::all();

        return view('admin.administrarEvento', compact('eventosDiarios', 'eventosSemanales', 'categorias'));
    }

    public function edit($id)
    {
        $evento = Evento::findOrFail($id);
        $semanales = Evento::where('tipo', 'semanal')->get();
        $categorias = Categoria::all();

        return view('admin.editarEvento', compact('evento', 'semanales', 'categorias'));
    }

    public function update(Request $request, $id)
    {
        $evento = Evento::findOrFail($id);
        $tipoAnterior = $evento->tipo;

        $request->merge([
            'hora' => $request->hora ? substr($request->hora, 0, 5) : null,
            'hora_termino' => $request->hora_termino ? substr($request->hora_termino, 0, 5) : null,
        ]);

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

        $normalizados = $request->all();
        $normalizados['hora'] = $normalizados['hora'] ? substr($normalizados['hora'], 0, 5) : null;
        $normalizados['hora_termino'] = $normalizados['hora_termino'] ? substr($normalizados['hora_termino'], 0, 5) : null;
        $normalizados['descripcion'] = trim(strip_tags($normalizados['descripcion']));
        $descripcionAnterior = trim(strip_tags($evento->descripcion));

        $cambios = [];
        $campos = ['tipo', 'titulo', 'fecha', 'lugar', 'descripcion', 'hora', 'hora_termino'];
        foreach ($campos as $campo) {
            $valorAnterior = $campo === 'descripcion' ? $descripcionAnterior : $evento->$campo;
            $valorNuevo = $normalizados[$campo] ?? null;

            if ($valorAnterior instanceof \Carbon\Carbon) {
                $valorAnterior = $valorAnterior->format('Y-m-d');
            }
            if (in_array($campo, ['hora', 'hora_termino']) && $valorAnterior) {
                $valorAnterior = substr($valorAnterior, 0, 5);
            }

            if ($valorAnterior != $valorNuevo) {
                $cambios[$campo] = ['antes' => $valorAnterior, 'después' => $valorNuevo];
            }
        }

        if ($tipoAnterior === 'semanal' && $request->tipo === 'diario') {
            Evento::where('id_evento_padre', $id)->delete();
        }

        $evento->update($request->all());

        if (count($cambios) > 0) {
            $inscripciones = Inscripcion::where('id_evento', $evento->id)->get();
            foreach ($inscripciones as $inscrito) {
                $rut = $inscrito->rut_usuario;
                $tipo = $inscrito->tipo_usuario;
                $usuario = $tipo === 'estudiante'
                    ? \App\Models\Estudiante::where('rut', $rut)->first()
                    : \App\Models\Externo::where('rut', $rut)->first();

                $correo = $usuario->correo ?? $usuario->email ?? null;
                $nombre = $usuario->nombre ?? 'Usuario';

                if ($correo) {
                    Mail::to($correo)->send(new NotificacionCambioEventoMail($nombre, $evento, $cambios));
                }
            }
        }

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
        $eventoSemanal = Evento::findOrFail($id);
        $eventosDiarios = Evento::where('id_evento_padre', $id)
            ->where('tipo', 'diario')
            ->orderBy('fecha')
            ->get();

        return view('admin.diasAsociados', compact('eventoSemanal', 'eventosDiarios'));
    }

    public function verDiasUsuario($id)
    {
        $eventoSemanal = Evento::findOrFail($id);
        $eventosDiarios = Evento::where('id_evento_padre', $id)
            ->orderBy('fecha')
            ->get();

        $rut = session('rut');
        $inscritos = Inscripcion::where('rut_usuario', $rut)->pluck('id_evento')->toArray();
        $hoy = Carbon::today();

        return view('user.verDiasEvento', compact('eventoSemanal', 'eventosDiarios', 'inscritos', 'hoy'));
    }

    public function administrarEventos(Request $request)
    {
        $categoriaId = $request->categoria_id;
        $eventos = Evento::when($categoriaId, fn($q) => $q->where('categoria_id', $categoriaId))->get();
        $eventosDiarios = $eventos->where('tipo', 'diario')->whereNull('id_evento_padre');
        $eventosSemanales = $eventos->where('tipo', 'semanal');
        $categorias = Categoria::all();

        return view('admin.administrarEvento', compact('eventosDiarios', 'eventosSemanales', 'categorias'));
    }

    public function mostrarEventosUsuarios(Request $request)
    {
        $rut = session('rut');
        $buscar = $request->input('buscar');
        $categoriaId = $request->input('categoria_id');
        $ahora = Carbon::now();

        // ======================
        // Eventos diarios (sin padre)
        // ======================
        $eventosDiarios = Evento::where('tipo', 'diario')
            ->whereNull('id_evento_padre')
            ->when($categoriaId, fn($q) => $q->where('categoria_id', $categoriaId))
            ->when($buscar, fn($q) => $q->where('titulo', 'like', "%$buscar%"))
            ->whereRaw("CONCAT(fecha, ' ', IFNULL(hora,'00:00')) >= ?", [$ahora->format('Y-m-d H:i')])
            ->orderBy('fecha')
            ->orderBy('hora')
            ->get();

        // ======================
        // Hijos diarios de eventos semanales
        // ======================
        $hijosSemanales = Evento::whereNotNull('id_evento_padre')
            ->where('tipo', 'diario')
            ->when($categoriaId, fn($q) => $q->where('categoria_id', $categoriaId))
            ->when($buscar, fn($q) => $q->where('titulo', 'like', "%$buscar%"))
            ->whereRaw("CONCAT(fecha, ' ', IFNULL(hora,'00:00')) >= ?", [$ahora->format('Y-m-d H:i')])
            ->orderBy('fecha')
            ->orderBy('hora')
            ->get();

        // ======================
        // Eventos semanales principales
        // ======================
        $eventosSemanales = Evento::where('tipo', 'semanal')
        ->when($categoriaId, fn($q) => $q->where('categoria_id', $categoriaId))
        ->when($buscar, fn($q) => $q->where('titulo', 'like', "%$buscar%"))
        ->get()
        ->filter(function($evento) use ($ahora) {
            $finSemana = Carbon::parse($evento->fecha)->copy()->addDays(6); // Semana de 7 días
            return $finSemana->endOfDay()->gte($ahora);
        });

        // ======================
        // Combinar diarios simples y hijos de semanales
        // ======================
        $eventosDiarios = $eventosDiarios->merge($hijosSemanales)->sort(function($a, $b) {
            $fechaHoraA = Carbon::parse($a->fecha . ' ' . ($a->hora ?? '00:00'));
            $fechaHoraB = Carbon::parse($b->fecha . ' ' . ($b->hora ?? '00:00'));
            return $fechaHoraA->getTimestamp() <=> $fechaHoraB->getTimestamp();
        });

        // ======================
        // Inscripciones y categorías
        // ======================
        $inscripciones = Inscripcion::where('rut_usuario', $rut)->get()->keyBy('id_evento');
        $categorias = Categoria::all();

        return view('user.inscripcionEventos', compact(
            'eventosDiarios',
            'eventosSemanales',
            'categorias',
            'inscripciones',
            'buscar'
        ));
    }
}