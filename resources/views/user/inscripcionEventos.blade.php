<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Eventos Disponibles</title>
  <link rel="stylesheet" href="{{ asset('css/ckeditor-content.css') }}">
  <script src="https://cdn.tailwindcss.com"></script>
</head>
@php use Carbon\Carbon; @endphp
<body class="bg-gradient-to-br from-gray-100 to-gray-200 min-h-screen font-sans">
  <div class="max-w-7xl mx-auto py-10 px-6">
    <div class="flex flex-col sm:flex-row justify-between sm:items-center mb-8 gap-2 sm:gap-0">
      <h1 class="text-4xl font-bold text-[#2D6A4F]">Eventos Disponibles</h1>
      <div class="flex gap-4 text-sm">
        <a href="{{ route('cuenta.formulario') }}" class="text-[#2D6A4F] hover:underline">Editar mi cuenta</a>
        <a href="/logout" class="text-red-600 hover:underline">Cerrar Sesi&oacute;n</a>
      </div>
    </div>

    @if(session('success'))
      <div id="success-message" class="mb-5 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg shadow">
        {{ session('success') }}
      </div>
      <script>
        setTimeout(() => {
          const msg = document.getElementById('success-message');
          if (msg) {
            msg.style.opacity = '0';
            setTimeout(() => msg.remove(), 500);
          }
        }, 3000);
      </script>
    @endif

    <!-- Eventos Diarios -->
    <section class="mb-16">
      <h2 class="text-2xl font-semibold text-gray-800 mb-6">Eventos Diarios</h2>
      <div class="grid sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($eventosDiarios as $evento)
        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-200 hover:shadow-xl transition">
          <h3 class="text-xl font-semibold text-[#2D6A4F] mb-2">{{ $evento->titulo }}</h3>
          <p class="text-sm text-gray-600"><strong>Fecha:</strong> {{ $evento->fecha }}</p>
          <p class="text-sm text-gray-600">
            <strong>Horario:</strong> {{ Carbon::parse($evento->hora)->format('H:i') }}
            @if($evento->hora_termino)
              - {{ Carbon::parse($evento->hora_termino)->format('H:i') }}
            @endif
          </p>
          <p class="text-sm text-gray-600"><strong>Lugar:</strong> {{ $evento->lugar }}</p>
          <div class="text-sm text-gray-700 mt-3">
            <strong>Descripción:</strong>
            <button class="ver-mas-btn text-[#2D6A4F] hover:underline" data-id="{{ $evento->id }}">Ver más</button>
          </div>
          <div class="mt-4">
            @php $estado = $inscripciones[$evento->id]->estado ?? null; @endphp
            @if ($estado === 'inscrito')
              <form method="POST" action="{{ route('desinscribirse', $evento->id) }}" onsubmit="return confirm('¿Estás seguro que deseas desinscribirte? Esta acción es irreversible.')">
                @csrf
                @method('DELETE')
                <p class="text-xs text-red-500 italic mb-2">⚠️ Esta acción es irreversible.</p>
                <button type="submit" class="bg-red-500 hover:bg-red-600 text-white py-2 px-4 rounded-xl transition">Desinscribirse</button>
              </form>
            @elseif ($estado === 'desinscrito')
              <div class="text-sm text-gray-500 italic">Ya te desinscribiste. No puedes volver a inscribirte.</div>
            @else
              <form method="POST" action="{{ route('inscribirse', $evento->id) }}" onsubmit="return confirm('¿Deseas inscribirte?')">
                @csrf
                <button type="submit" class="bg-[#2D6A4F] hover:bg-[#22543D] text-white py-2 px-4 rounded-xl transition">Inscribirse</button>
              </form>
            @endif
          </div>

          <div id="modal-{{ $evento->id }}" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden justify-center items-center">
            <div class="bg-white rounded-xl max-w-xl w-full p-6 shadow-2xl overflow-y-auto max-h-[90vh] relative">
              <h2 class="text-2xl font-bold text-[#2D6A4F] mb-4">{{ $evento->titulo }}</h2>
              <p class="text-sm text-gray-600"><strong>Fecha:</strong> {{ $evento->fecha }}</p>
              <p class="text-sm text-gray-600"><strong>Horario:</strong> {{ Carbon::parse($evento->hora)->format('H:i') }} @if($evento->hora_termino) - {{ Carbon::parse($evento->hora_termino)->format('H:i') }} @endif</p>
              <p class="text-sm text-gray-600 mb-4"><strong>Lugar:</strong> {{ $evento->lugar }}</p>
              <div class="editor-content text-sm text-gray-700">{!! $evento->descripcion !!}</div>
              <button class="cerrar-modal-btn absolute top-2 right-4 text-gray-500 hover:text-gray-700 text-xl" data-id="{{ $evento->id }}">&times;</button>
            </div>
          </div>
        </div>
        @empty
        <p class="text-gray-500 italic">No hay eventos diarios disponibles.</p>
        @endforelse
      </div>
    </section>

    <!-- Eventos Semanales -->
    <section>
      <h2 class="text-2xl font-semibold text-gray-800 mb-6">Eventos Semanales</h2>
      <div class="grid md:grid-cols-2 gap-6">
        @forelse($eventosSemanales as $evento)
        @php
          $inicioSemana = Carbon::parse($evento->fecha)->startOfWeek();
          $finSemana = $inicioSemana->copy()->addDays(6);
        @endphp
        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-200 hover:shadow-xl transition">
          <h3 class="text-xl font-semibold text-[#2D6A4F]">{{ $evento->titulo }}</h3>
          <p class="text-sm text-gray-600"><strong>Semana:</strong> {{ $inicioSemana->format('Y-m-d') }} al {{ $finSemana->format('Y-m-d') }}</p>
          <p class="text-sm text-gray-600"><strong>Lugar:</strong> {{ $evento->lugar }}</p>
          <div class="text-sm text-gray-700 mt-3">
            <strong>Descripción:</strong>
            <button class="ver-mas-btn text-[#2D6A4F] hover:underline" data-id="{{ $evento->id }}">Ver más</button>
          </div>
          <div class="mt-4">
            <a href="{{ route('usuario.evento.dias', $evento->id) }}" class="text-sm text-[#2D6A4F] hover:underline">Ver días disponibles</a>
          </div>

          <div id="modal-{{ $evento->id }}" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden justify-center items-center">
            <div class="bg-white rounded-xl max-w-xl w-full p-6 shadow-2xl overflow-y-auto max-h-[90vh] relative">
              <h2 class="text-2xl font-bold text-[#2D6A4F] mb-4">{{ $evento->titulo }}</h2>
              <p class="text-sm text-gray-600"><strong>Semana:</strong> {{ $inicioSemana->format('Y-m-d') }} al {{ $finSemana->format('Y-m-d') }}</p>
              <p class="text-sm text-gray-600 mb-4"><strong>Lugar:</strong> {{ $evento->lugar }}</p>
              <div class="editor-content text-sm text-gray-700">{!! $evento->descripcion !!}</div>
              <button class="cerrar-modal-btn absolute top-2 right-4 text-gray-500 hover:text-gray-700 text-xl" data-id="{{ $evento->id }}">&times;</button>
            </div>
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
        const id = btn.dataset.id;
        const modal = document.getElementById(`modal-${id}`);
        if (modal) {
          modal.classList.remove('hidden');
          modal.classList.add('flex');
        }
      });
    });

    document.querySelectorAll('.cerrar-modal-btn').forEach(btn => {
      btn.addEventListener('click', () => {
        const id = btn.dataset.id;
        const modal = document.getElementById(`modal-${id}`);
        if (modal) {
          modal.classList.remove('flex');
          modal.classList.add('hidden');
        }
      });
    });
  </script>
</body>
</html>
