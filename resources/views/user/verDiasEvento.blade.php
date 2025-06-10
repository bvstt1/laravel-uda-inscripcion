<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Días del Evento Semanal</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap">
  <style>
    body { font-family: 'Inter', sans-serif; }
  </style>
</head>
@php use Carbon\Carbon; @endphp
<body class="bg-gray-50 min-h-screen text-gray-800">
  <div class="max-w-5xl mx-auto py-10 px-6">
    <!-- Header -->
    <div class="flex justify-between items-center mb-8">
      <h1 class="text-3xl font-bold text-[#1B4332]">Días disponibles para: "{{ $eventoSemanal->titulo }}"</h1>
      <a href="{{ route('inscripcionEventos') }}" class="text-sm text-blue-600 hover:underline">&larr; Volver</a>
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
      <div class="grid md:grid-cols-2 gap-6">
        @foreach($eventosDiarios as $evento)
          <div class="bg-white rounded-xl shadow-md p-6 relative">
            <h3 class="text-xl font-semibold text-[#1B4332]">{{ $evento->titulo }}</h3>
            <p class="text-sm"><strong>Fecha:</strong> {{ $evento->fecha }}</p>
            <p class="text-sm">
              <strong>Horario:</strong> {{ Carbon::parse($evento->hora)->format('H:i') }}
              @if($evento->hora_termino)
                - {{ Carbon::parse($evento->hora_termino)->format('H:i') }}
              @endif
            </p>
            <p class="text-sm"><strong>Lugar:</strong> {{ $evento->lugar }}</p>
            <div class="mt-2">
              <button class="ver-mas-btn text-sm text-[#1B4332] hover:underline" data-id="{{ $evento->id }}">Ver más</button>
            </div>

            <!-- Modal -->
            <div id="modal-{{ $evento->id }}" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden justify-center items-center">
              <div class="bg-white rounded-lg max-w-xl w-full p-6 shadow-xl relative">
                <h2 class="text-2xl font-bold text-[#1B4332] mb-3">{{ $evento->titulo }}</h2>
                <p class="text-sm mb-1"><strong>Fecha:</strong> {{ $evento->fecha }}</p>
                <p class="text-sm mb-1">
                  <strong>Horario:</strong> {{ Carbon::parse($evento->hora)->format('H:i') }}
                  @if($evento->hora_termino)
                    - {{ Carbon::parse($evento->hora_termino)->format('H:i') }}
                  @endif
                </p>
                <p class="text-sm mb-4"><strong>Lugar:</strong> {{ $evento->lugar }}</p>
                <div class="editor-content text-sm">{!! $evento->descripcion !!}</div>
                <button class="cerrar-modal-btn absolute top-2 right-4 text-gray-500 hover:text-gray-700 text-xl" data-id="{{ $evento->id }}">&times;</button>
              </div>
            </div>

            <div class="mt-4">
              @if(in_array($evento->id, $inscritos))
                <form method="POST" action="{{ route('desinscribirse', $evento->id) }}" onsubmit="return confirm('Desinscribirse es irreversible. ¿Continuar?')">
                  @csrf
                  @method('DELETE')
                  <p class="text-xs text-red-500 italic mb-2">⚠️ Esta acción es irreversible.</p>
                  <button type="submit" class="bg-red-500 hover:bg-red-600 text-white py-1 px-4 rounded transition">Desinscribirse</button>
                </form>
              @else
                @php
                  $eventoDateTime = Carbon::parse("{$evento->fecha} {$evento->hora}");
                @endphp
                @if($eventoDateTime->lt(Carbon::now()))
                  <p class="text-xs text-gray-400 italic mt-2">Este evento ya ha pasado.</p>
                @else
                  <form method="POST" action="{{ route('inscribirse', $evento->id) }}" onsubmit="return confirm('¿Deseas inscribirte?')">
                    @csrf
                    <button type="submit" class="bg-[#1B4332] hover:bg-[#14532D] text-white py-1 px-4 rounded transition">Inscribirse</button>
                  </form>
                @endif
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
