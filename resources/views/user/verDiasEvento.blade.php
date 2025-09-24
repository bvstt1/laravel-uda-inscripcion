<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Días del Evento Semanal</title>
  <link rel="stylesheet" href="{{ asset('css/ckeditor-content.css') }}">
  @vite('resources/css/app.css')
  @vite('resources/js/app.js')
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap">
  <style>
    body { font-family: 'Inter', sans-serif; }
  </style>
</head>
@php use Carbon\Carbon; @endphp
<body class="bg-gray-50 min-h-screen text-gray-800">
  <div class="max-w-7xl mx-auto py-10 px-6">
    <!-- Header -->
    <div class="flex justify-between items-center mb-8">
      <h1 class="text-3xl font-bold text-[#1B4332]">📅 Días disponibles para: <span class="italic">"{{ $eventoSemanal->titulo }}"</span></h1>
      <a href="{{ route('inscripcionEventos') }}" class="inline-block px-3 py-1 bg-emerald-100 text-emerald-800 rounded-md hover:bg-emerald-200 transition">&larr; Volver</a>
    </div>

    <!-- Flash Message -->
    @if(session('success'))
      <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-5">
        {{ session('success') }}
      </div>
    @endif

    @if($eventosDiarios->isEmpty())
      <p class="text-gray-500 italic">No hay eventos diarios registrados para esta semana.</p>
    @else
      <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($eventosDiarios as $evento)
          @php
            $eventoDateTime = Carbon::parse("{$evento->fecha} {$evento->hora}");
          @endphp

          <div class="bg-white border-l-8 rounded-xl shadow-md hover:shadow-lg p-6 border border-gray-100 transition relative border-l-[#328E6E]">
            <h3 class="text-lg font-semibold text-[#328E6E]">{{ $evento->titulo }}</h3>
            <p class="text-xs font-semibold text-gray-500 mt-1">Asociado a semana</p>
            <p class="text-sm text-gray-700 mt-1"><strong>Fecha:</strong> {{ $evento->fecha }}</p>
            <p class="text-sm text-gray-700"><strong>Horario:</strong> {{ Carbon::parse($evento->hora)->format('H:i') }} 
              @if($evento->hora_termino) - {{ Carbon::parse($evento->hora_termino)->format('H:i') }} @endif
            </p>
            <p class="text-sm text-gray-700"><strong>Lugar:</strong> {{ $evento->lugar }}</p>

            <button class="ver-mas-btn text-sm text-[#328E6E] hover:underline mt-2" data-id="{{ $evento->id }}">Ver más</button>

            <!-- Modal -->
            <div id="modal-{{ $evento->id }}" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden justify-center items-center">
              <div class="bg-white p-6 rounded-xl max-w-md w-full relative">
                <h4 class="text-lg font-bold mb-2 text-[#2D6A4F]">{{ $evento->titulo }}</h4>
                <p class="text-sm text-gray-700 mb-2"><strong>Descripción:</strong></p>
                <div class="text-sm text-gray-600">{!! $evento->descripcion !!}</div>
                <button class="cerrar-modal-btn absolute top-2 right-2 text-gray-500 hover:text-red-500 text-xl" data-id="{{ $evento->id }}">&times;</button>
              </div>
            </div>

            <div class="mt-4">
              @if(in_array($evento->id, $inscritos))
                <form method="POST" action="{{ route('desinscribirse', $evento->id) }}" onsubmit="return confirm('¿Estás seguro que deseas desinscribirte?')">
                  @csrf @method('DELETE')
                  <p class="text-xs text-red-500 italic mb-2">⚠️ Esta acción es irreversible.</p>
                  <button type="submit" class="w-full bg-red-500 hover:bg-red-600 text-white py-2 px-4 rounded-xl transition">Desinscribirse</button>
                </form>
              @elseif($eventoDateTime->lt(Carbon::now()))
                <p class="text-xs text-gray-400 italic mt-2">Este evento ya ha pasado.</p>
              @else
                <form method="POST" action="{{ route('inscribirse', $evento->id) }}" onsubmit="return confirm('¿Deseas inscribirte?')">
                  @csrf
                  <button type="submit" class="w-full bg-[#2D6A4F] hover:bg-[#1B4332] text-white py-2 px-4 rounded-xl transition">Inscribirse</button>
                </form>
              @endif
            </div>
          </div>
        @endforeach
      </div>
    @endif
  </div>

  <script>
    document.querySelectorAll('.ver-mas-btn').forEach(btn => {
      btn.addEventListener('click', () => {
        const modal = document.getElementById(`modal-${btn.dataset.id}`);
        modal?.classList.remove('hidden');
        modal?.classList.add('flex');
      });
    });

    document.querySelectorAll('.cerrar-modal-btn').forEach(btn => {
      btn.addEventListener('click', () => {
        const modal = document.getElementById(`modal-${btn.dataset.id}`);
        modal?.classList.remove('flex');
        modal?.classList.add('hidden');
      });
    });
  </script>
</body>
</html>
