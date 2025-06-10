<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Eventos Disponibles</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://unpkg.com/@popperjs/core@2"></script>
  <script src="https://unpkg.com/tippy.js@6"></script>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap">
  <style>
    body { font-family: 'Inter', sans-serif; }
  </style>
</head>
@php use Carbon\Carbon; @endphp
<body class="bg-gray-50 min-h-screen text-gray-800">
  <div class="max-w-7xl mx-auto py-10 px-6">
    
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-center mb-10 gap-4">
      <h1 class="text-4xl font-bold text-[#1B4332]">📅 Eventos Disponibles</h1>
      <div class="flex gap-4 text-sm">
        <a href="{{ route('cuenta.formulario') }}" class="text-[#1B4332] hover:underline">👤 Mi cuenta</a>
        <a href="/logout" class="text-red-600 hover:underline">🔒 Cerrar Sesión</a>
      </div>
    </div>

    <!-- Flash message -->
    @if(session('success'))
      <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-5">
        {{ session('success') }}
      </div>
    @endif

    <!-- Eventos Diarios -->
    <section class="mb-16">
      <h2 class="text-2xl font-semibold mb-4 text-[#2D6A4F]">Eventos Diarios</h2>
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($eventosDiarios->sortBy('prioridad') as $evento)
        <div class="bg-white border border-gray-200 rounded-2xl shadow hover:shadow-lg transition p-6 relative">
          @if($evento->prioridad === 1)
            <span class="absolute top-2 right-2 bg-yellow-300 text-yellow-900 text-xs font-semibold px-3 py-1 rounded-full">★ Prioritario</span>
          @endif
          <h3 class="text-xl font-semibold mb-1">{{ $evento->titulo }}</h3>
          <p class="text-sm"><strong>Fecha:</strong> {{ $evento->fecha }}</p>
          <p class="text-sm"><strong>Horario:</strong> {{ Carbon::parse($evento->hora)->format('H:i') }} @if($evento->hora_termino) - {{ Carbon::parse($evento->hora_termino)->format('H:i') }} @endif</p>
          <p class="text-sm"><strong>Lugar:</strong> {{ $evento->lugar }}</p>
          <div class="mt-3 text-sm text-gray-700">
            <button class="ver-mas-btn text-[#1B4332] hover:underline" data-id="{{ $evento->id }}">Ver más</button>
          </div>

          <div class="mt-4">
            @php $estado = $inscripciones[$evento->id]->estado ?? null; @endphp
            @if ($estado === 'inscrito')
              <form method="POST" action="{{ route('desinscribirse', $evento->id) }}" onsubmit="return confirm('¿Estás seguro que deseas desinscribirte?')">
                @csrf @method('DELETE')
                <button type="submit" class="w-full bg-red-500 hover:bg-red-600 text-white py-2 px-4 rounded-xl transition">Desinscribirse</button>
              </form>
            @elseif ($estado === 'desinscrito')
              <div class="text-sm text-gray-500 italic mt-2">Ya te desinscribiste.</div>
            @else
              <form method="POST" action="{{ route('inscribirse', $evento->id) }}" onsubmit="return confirm('¿Deseas inscribirte?')">
                @csrf
                <button type="submit" class="w-full bg-[#2D6A4F] hover:bg-[#1B4332] text-white py-2 px-4 rounded-xl transition">Inscribirse</button>
              </form>
            @endif
          </div>
        </div>

        <!-- Modal -->
        <div id="modal-{{ $evento->id }}" class="fixed inset-0 bg-black bg-opacity-40 z-50 hidden justify-center items-center">
          <div class="bg-white rounded-lg p-6 max-w-xl w-full relative">
            <h2 class="text-2xl font-bold text-[#2D6A4F] mb-3">{{ $evento->titulo }}</h2>
            <p class="text-sm mb-1"><strong>Fecha:</strong> {{ $evento->fecha }}</p>
            <p class="text-sm mb-1"><strong>Horario:</strong> {{ Carbon::parse($evento->hora)->format('H:i') }} @if($evento->hora_termino) - {{ Carbon::parse($evento->hora_termino)->format('H:i') }} @endif</p>
            <p class="text-sm mb-3"><strong>Lugar:</strong> {{ $evento->lugar }}</p>
            <div class="editor-content text-sm">{!! $evento->descripcion !!}</div>
            <button class="cerrar-modal-btn absolute top-2 right-4 text-xl text-gray-500 hover:text-gray-700" data-id="{{ $evento->id }}">&times;</button>
          </div>
        </div>
        @empty
        <p class="text-gray-500 italic">No hay eventos diarios disponibles.</p>
        @endforelse
      </div>
    </section>

    <!-- Eventos Semanales -->
    <section>
      <h2 class="text-2xl font-semibold mb-4 text-[#2D6A4F]">Eventos Semanales</h2>
      <div class="grid md:grid-cols-2 gap-6">
        @forelse($eventosSemanales->sortBy('prioridad') as $evento)
        @php
          $inicioSemana = Carbon::parse($evento->fecha)->startOfWeek();
          $finSemana = $inicioSemana->copy()->addDays(6);
        @endphp
        <div class="bg-white border border-gray-200 rounded-2xl shadow hover:shadow-lg transition p-6 relative">
          @if($evento->prioridad === 1)
            <span class="absolute top-2 right-2 bg-yellow-300 text-yellow-900 text-xs font-semibold px-3 py-1 rounded-full">★ Prioritario</span>
          @endif
          <h3 class="text-xl font-semibold">{{ $evento->titulo }}</h3>
          <p class="text-sm"><strong>Semana:</strong> {{ $inicioSemana->format('Y-m-d') }} al {{ $finSemana->format('Y-m-d') }}</p>
          <p class="text-sm"><strong>Lugar:</strong> {{ $evento->lugar }}</p>
          <div class="mt-3 text-sm text-gray-700">
            <button class="ver-mas-btn text-[#1B4332] hover:underline" data-id="{{ $evento->id }}">Ver más</button>
          </div>
          <div class="mt-4">
            <a href="{{ route('usuario.evento.dias', $evento->id) }}" class="block w-full text-center bg-[#2D6A4F] hover:bg-[#1B4332] text-white py-2 px-4 rounded-xl transition">
              Ver días disponibles
            </a>
          </div>
        </div>

        <!-- Modal -->
        <div id="modal-{{ $evento->id }}" class="fixed inset-0 bg-black bg-opacity-40 z-50 hidden justify-center items-center">
          <div class="bg-white rounded-lg p-6 max-w-xl w-full relative">
            <h2 class="text-2xl font-bold text-[#2D6A4F] mb-3">{{ $evento->titulo }}</h2>
            <p class="text-sm mb-1"><strong>Semana:</strong> {{ $inicioSemana->format('Y-m-d') }} al {{ $finSemana->format('Y-m-d') }}</p>
            <p class="text-sm mb-3"><strong>Lugar:</strong> {{ $evento->lugar }}</p>
            <div class="editor-content text-sm">{!! $evento->descripcion !!}</div>
            <button class="cerrar-modal-btn absolute top-2 right-4 text-xl text-gray-500 hover:text-gray-700" data-id="{{ $evento->id }}">&times;</button>
          </div>
        </div>
        @empty
        <p class="text-gray-500 italic">No hay eventos semanales disponibles.</p>
        @endforelse
      </div>
    </section>
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
