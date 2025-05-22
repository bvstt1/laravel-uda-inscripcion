<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Panel de Administración de Eventos</title>
  <link rel="stylesheet" href="{{ asset('css/ckeditor-content.css') }}">
  <script src="https://cdn.tailwindcss.com"></script>
</head>
@php use Carbon\Carbon; @endphp
<body class="bg-gray-100 min-h-screen font-sans">
  <div class="max-w-6xl mx-auto py-10 px-6">
    <div class="flex justify-between items-center mb-8">
      <h1 class="text-3xl font-bold text-[#328E6E]">Panel de Administración</h1>
      <div>
        <a href="{{ route('panel') }}" class="text-sm text-blue-600 hover:underline mr-2">&larr; Volver al panel</a>
        <a href="/logout" class="text-sm text-red-600 hover:underline">Cerrar Sesión</a>
      </div>
    </div>

    @if(session('success'))
    <div id="success-message" class="mb-6 p-4 bg-green-100 text-green-800 rounded-md border border-green-300 transition-opacity duration-500">
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

    <div class="mb-8">
      <a href="{{ route('eventos.create') }}" class="bg-[#328E6E] hover:bg-[#287256] text-white py-2 px-6 rounded-lg shadow-md transition">
        + Crear nuevo evento
      </a>
    </div>

    <section class="mb-12">
      <h2 class="text-xl font-semibold text-gray-800 mb-4">Eventos Diarios (sin semana asociada)</h2>
      <div class="grid md:grid-cols-2 gap-6">
        @forelse($eventosDiarios as $evento)
        <div class="bg-white rounded-xl shadow-md p-6">
          <h3 class="text-lg font-semibold text-[#328E6E]">{{ $evento->titulo }}</h3>
          <p class="text-sm text-gray-600"><strong>Fecha:</strong> {{ $evento->fecha }}</p>
          <p class="text-sm text-gray-600">
            <strong>Horario:</strong> {{ Carbon::parse($evento->hora)->format('H:i') }}
            @if($evento->hora_termino)
              - {{ Carbon::parse($evento->hora_termino)->format('H:i') }}
            @endif
          </p>
          <p class="text-sm text-gray-600"><strong>Lugar:</strong> {{ $evento->lugar }}</p>
          <button class="ver-mas-btn text-sm text-[#328E6E] hover:underline ml-auto" data-id="{{ $evento->id }}">Ver más</button>
          <div class="mt-4 flex gap-2">
            <a href="{{ route('eventos.edit', $evento->id) }}" class="text-sm bg-blue-500 hover:bg-blue-600 text-white px-4 py-1 rounded-lg">Editar</a>
            <form action="{{ route('eventos.destroy', $evento->id) }}" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar este evento?')">
              @csrf
              @method('DELETE')
              <button type="submit" class="text-sm bg-red-500 hover:bg-red-600 text-white px-4 py-1 rounded-lg">Eliminar</button>
            </form>
          </div>
        </div>

        <!-- Modal -->
        <div id="modal-{{ $evento->id }}" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden justify-center items-center">
          <div class="bg-white rounded-lg max-w-xl w-full p-6 shadow-xl overflow-y-auto max-h-[90vh] relative">
            <h2 class="text-2xl font-bold text-[#328E6E] mb-4">{{ $evento->titulo }}</h2>
            <p class="text-sm text-gray-600 mb-1"><strong>Fecha:</strong> {{ $evento->fecha }}</p>
            <p class="text-sm text-gray-600 mb-1">
              <strong>Horario:</strong> {{ Carbon::parse($evento->hora)->format('H:i') }}
              @if($evento->hora_termino)
                - {{ Carbon::parse($evento->hora_termino)->format('H:i') }}
              @endif
            </p>
            <p class="text-sm text-gray-600 mb-4"><strong>Lugar:</strong> {{ $evento->lugar }}</p>
            <div class="editor-content text-sm text-gray-700"><strong>Descripción:</strong>
              {!! $evento->descripcion !!}
            </div>            
            <button class="cerrar-modal-btn absolute top-2 right-4 text-gray-500 hover:text-gray-700 text-xl" data-id="{{ $evento->id }}">&times;</button>
          </div>
        </div>
        @empty
        <p class="text-gray-500 italic">No hay eventos diarios registrados.</p>
        @endforelse
      </div>
    </section>

    <section class="mb-12">
      <h2 class="text-xl font-semibold text-gray-800 mb-4">Eventos Semanales</h2>
      <div class="grid md:grid-cols-2 gap-6">
        @forelse($eventosSemanales as $evento)
        <div class="bg-white rounded-xl shadow-md p-6">
          <h3 class="text-lg font-semibold text-[#328E6E]">{{ $evento->titulo }}</h3>
          @php
              $inicioSemana = Carbon::parse($evento->fecha)->startOfWeek();
              $finSemana = $inicioSemana->copy()->addDays(6);
          @endphp
          <p class="text-sm text-gray-600"><strong>Semana:</strong> Desde {{ $inicioSemana->format('Y-m-d') }} al {{ $finSemana->format('Y-m-d') }}</p>
          <p class="text-sm text-gray-600"><strong>Lugar:</strong> {{ $evento->lugar }}</p>
          <button class="ver-mas-btn text-sm text-[#328E6E] hover:underline ml-auto" data-id="{{ $evento->id }}">Ver más</button>
          <div class="mt-4 flex gap-2">
            <a href="{{ route('eventos.edit', $evento->id) }}" class="text-sm bg-blue-500 hover:bg-blue-600 text-white px-4 py-1 rounded-lg">Editar</a>
            <form action="{{ route('eventos.destroy', $evento->id) }}" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar este evento?')">
              @csrf
              @method('DELETE')
              <button type="submit" class="text-sm bg-red-500 hover:bg-red-600 text-white px-4 py-1 rounded-lg">Eliminar</button>
            </form>
            <a href="{{ route('eventos.semanal.dias', $evento->id) }}" class="text-sm text-[#328E6E] hover:underline ml-auto">Ver días</a>
          </div>
        </div>

        <!-- Modal semanal -->
        <div id="modal-{{ $evento->id }}" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden justify-center items-center">
          <div class="bg-white rounded-lg max-w-xl w-full p-6 shadow-xl overflow-y-auto max-h-[90vh] relative">
            <h2 class="text-2xl font-bold text-[#328E6E] mb-4">{{ $evento->titulo }}</h2>
            <p class="text-sm text-gray-600 mb-1"><strong>Semana:</strong> Desde {{ $inicioSemana->format('Y-m-d') }} al {{ $finSemana->format('Y-m-d') }}</p>
            <p class="text-sm text-gray-600 mb-4"><strong>Lugar:</strong> {{ $evento->lugar }}</p>
            <div class="editor-content text-sm text-gray-700"><strong>Descripción:</strong>
              {!! $evento->descripcion !!}
            </div>
            <button class="cerrar-modal-btn absolute top-2 right-4 text-gray-500 hover:text-gray-700 text-xl" data-id="{{ $evento->id }}">&times;</button>
          </div>
        </div>
        @empty
        <p class="text-gray-500 italic">No hay eventos semanales registrados.</p>
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
