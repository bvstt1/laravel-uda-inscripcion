<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Días del Evento Semanal</title>
  @vite('resources/css/app.css')
  @vite('resources/js/app.js')
</head>
@php use Carbon\Carbon; @endphp
<body class="bg-gray-100 min-h-screen font-sans">
  <div class="max-w-7xl mx-auto py-12 px-6">
    <div class="flex justify-between items-center mb-8">
      <h1 class="text-3xl font-bold text-[#328E6E]">📅 Eventos diarios asociados a: "{{ $eventoSemanal->titulo }}"</h1>
      <a href="{{ route('eventos.index') }}" class="inline-block px-3 py-1 bg-emerald-100 text-emerald-800 rounded-md hover:bg-emerald-200 transition">&larr; Volver al panel</a>
    </div>

    @if($eventosDiarios->isEmpty())
      <p class="text-gray-500 italic">No hay eventos diarios registrados para esta semana.</p>
    @else
      <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
        @php
          $eventosOrdenados = $eventosDiarios->sortBy([
            fn($a, $b) => strcmp($a->fecha, $b->fecha) ?: strcmp($a->hora, $b->hora)
          ]);
        @endphp
        @foreach($eventosOrdenados as $evento)
          <div class="bg-white border-l-8 rounded-xl shadow-md hover:shadow-lg p-6 border border-gray-100 transition" style="border-color: {{ $evento->categoria->color ?? '#CBD5E0' }}">
            <h3 class="text-lg font-semibold text-[#328E6E]">{{ $evento->titulo }}</h3>
            <p class="text-xs font-semibold text-gray-500 mt-1">Categoría: {{ $evento->categoria->nombre ?? 'Sin categoría' }}</p>
            <p class="text-sm text-gray-700 mt-1"><strong>Fecha:</strong> {{ $evento->fecha }}</p>
            <p class="text-sm text-gray-700"><strong>Horario:</strong> {{ Carbon::parse($evento->hora)->format('H:i') }}
              @if($evento->hora_termino)
                - {{ Carbon::parse($evento->hora_termino)->format('H:i') }}
              @endif
            </p>
            <p class="text-sm text-gray-700"><strong>Lugar:</strong> {{ $evento->lugar }}</p>
            <div class="mt-4 flex gap-2">
              <a href="{{ route('eventos.edit', $evento->id) }}" class="text-sm bg-blue-500 hover:bg-blue-600 text-white px-4 py-1 rounded-lg">Editar</a>
              <form action="{{ route('eventos.destroy', $evento->id) }}" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar este evento?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="text-sm bg-red-500 hover:bg-red-600 text-white px-4 py-1 rounded-lg">Eliminar</button>
              </form>
              <button class="ver-mas-btn text-sm text-emerald-600 hover:underline ml-auto" data-id="{{ $evento->id }}">Ver más</button>
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
              <div class="prose prose-sm max-w-none text-gray-700"><strong>Descripción:</strong>{!! $evento->descripcion_html ?? $evento->descripcion !!}</div>
              <button class="cerrar-modal-btn absolute top-2 right-4 text-gray-500 hover:text-gray-700 text-xl" data-id="{{ $evento->id }}">&times;</button>
            </div>
          </div>
        @endforeach
      </div>
    @endif
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