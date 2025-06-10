<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Seleccionar Evento</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen py-12 px-4">
  <div class="max-w-5xl mx-auto">
    <a href="{{ route('panel') }}" class="absolute top-4 right-4 bg-[#328E6E] hover:bg-[#287256] text-white px-4 py-2 rounded transition text-sm">
      ← Volver al panel
    </a>

    <h1 class="text-3xl font-bold text-[#328E6E] mb-8 text-center">Selecciona un evento para registrar asistencia</h1>

    {{-- Eventos semanales agrupados --}}
    <h2 class="text-2xl font-semibold text-[#328E6E] mb-4">Eventos Semanales</h2>

    @forelse($eventosSemanales as $semana)
      <div class="mb-4 border border-gray-300 rounded-lg bg-white">
        <button onclick="toggleSemana({{ $semana->id }})"
          class="w-full text-left bg-gray-100 hover:bg-gray-200 px-4 py-2 font-semibold rounded-t-lg">
          📅 {{ $semana->titulo }} ({{ $semana->fecha }} – {{ \Carbon\Carbon::parse($semana->fecha)->addDays(6)->format('Y-m-d') }})
        </button>

        <div id="dias-{{ $semana->id }}" class="hidden px-4 py-4 space-y-4">
          @forelse($semana->dias as $evento)
            @php
              $bloqueado = now()->greaterThanOrEqualTo(
                \Carbon\Carbon::parse($evento->fecha . ' ' . $evento->hora_termino)->subMinutes(15)
              );
            @endphp

            <div class="border rounded-md p-4 shadow-sm">
              <h3 class="font-semibold text-[#328E6E]">{{ $evento->titulo }} ({{ $evento->fecha }})</h3>
              <p class="text-sm text-gray-600">Hora: {{ $evento->hora }} - {{ $evento->hora_termino }}</p>
              <p class="text-sm text-gray-600">Lugar: {{ $evento->lugar }}</p>

              @if ($bloqueado)
                <span class="inline-block bg-red-100 text-red-600 px-3 py-1 rounded mt-2 text-sm">Evento bloqueado</span>
              @else
                <a href="{{ route('totem.form', $evento->id) }}" class="inline-block bg-[#328E6E] hover:bg-[#287256] text-white px-3 py-1 mt-2 rounded text-sm">Ingresar al Tótem</a>
              @endif
            </div>
          @empty
            <p class="text-sm italic text-gray-500">No hay días asociados a esta semana.</p>
          @endforelse
        </div>
      </div>
    @empty
      <p class="text-gray-500 italic">No hay eventos semanales disponibles.</p>
    @endforelse

    {{-- Eventos diarios independientes --}}
    <hr class="my-8">
    <h2 class="text-2xl font-semibold text-[#328E6E] mb-4">Eventos Diarios Independientes</h2>

    @if($eventosDiariosIndependientes->isEmpty())
      <p class="text-gray-500 italic">No hay eventos diarios independientes disponibles.</p>
    @else
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @foreach($eventosDiariosIndependientes as $evento)
          @php
            $bloqueado = now()->greaterThanOrEqualTo(
              \Carbon\Carbon::parse($evento->fecha . ' ' . $evento->hora_termino)->subMinutes(15)
            );
          @endphp

          <div class="bg-white shadow rounded-lg p-6 text-center">
            <h2 class="text-xl font-semibold text-[#328E6E] mb-2">{{ $evento->titulo }}</h2>
            <p class="text-sm text-gray-600">
              <strong>Fecha:</strong> {{ $evento->fecha }}<br>
              <strong>Hora:</strong> {{ $evento->hora }} - {{ $evento->hora_termino }}<br>
              <strong>Lugar:</strong> {{ $evento->lugar }}
            </p>

            @if ($bloqueado)
              <span class="inline-block mt-4 bg-red-100 text-red-600 px-4 py-2 rounded cursor-not-allowed text-sm">Evento bloqueado</span>
            @else
              <a href="{{ route('totem.form', $evento->id) }}" class="mt-4 inline-block bg-[#328E6E] hover:bg-[#287256] text-white px-4 py-2 rounded transition text-sm">
                Ingresar al Tótem
              </a>
            @endif
          </div>
        @endforeach
      </div>
    @endif
  </div>

  <script>
    function toggleSemana(id) {
      const content = document.getElementById('dias-' + id);
      if (content) {
        content.classList.toggle('hidden');
      }
    }
  </script>
</body>
</html>
