<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Días del Evento Semanal</title>
  <link rel="stylesheet" href="{{ asset('css/ckeditor-content.css') }}">
  <script src="https://cdn.tailwindcss.com"></script>
</head>
@php use Carbon\Carbon; @endphp
<body class="bg-gray-100 min-h-screen font-sans">
  <div class="max-w-5xl mx-auto py-10 px-6">
    <div class="flex justify-between items-center mb-6">
      <h1 class="text-2xl font-bold text-[#328E6E]">
        Inscribirse a días de: "{{ $eventoSemanal->titulo }}"
      </h1>
      <a href="{{ route('inscripcionEventos') }}" class="text-sm text-blue-600 hover:underline">&larr; Volver a eventos</a>
    </div>

    @if($eventosDiarios->isEmpty())
      <p class="text-gray-500 italic">No hay eventos diarios registrados para esta semana.</p>
    @else
      <div class="grid md:grid-cols-2 gap-6">
        @foreach($eventosDiarios as $evento)
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
            <button class="ver-mas-btn text-sm text-[#328E6E] hover:underline mt-2" data-id="{{ $evento->id }}">Ver más</button>

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
                <div class="editor-content text-sm text-gray-700">{!! $evento->descripcion !!}</div>
                <button class="cerrar-modal-btn absolute top-2 right-4 text-gray-500 hover:text-gray-700 text-xl" data-id="{{ $evento->id }}">&times;</button>
              </div>
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
