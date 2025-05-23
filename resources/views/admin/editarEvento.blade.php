<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Editar Evento</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    @vite([
        'resources/css/app.css',
        'resources/css/ckeditor.css',
        'resources/js/ckeditor.js',
        'resources/js/app.js',
    ])
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="bg-white p-8 rounded-2xl shadow-md w-full max-w-lg lg:max-w-3xl">

        <div class="flex justify-end space-x-3 mb-6">
            <a href="{{ route('eventos.index') }}" class="text-xs bg-[#328E6E] hover:bg-[#287256] text-white font-medium py-1 px-3 rounded-lg shadow">← Volver</a>
            <a href="/logout" class="text-sm text-red-600 hover:underline">Cerrar sesión</a>
        </div>

        <h2 class="text-2xl font-bold text-center text-[#328E6E] mb-4">Editar Evento</h2>

        <form method="POST" action="{{ route('eventos.update', $evento->id) }}" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label for="tipo" class="block font-medium text-gray-700">Tipo de evento</label>
                <select name="tipo" id="tipo" required class="mt-1 block w-full border border-gray-300 rounded-lg shadow-sm">
                    <option value="semanal" {{ $evento->tipo == 'semanal' ? 'selected' : '' }}>Semanal</option>
                    <option value="diario" {{ $evento->tipo == 'diario' ? 'selected' : '' }}>Diario</option>
                </select>
            </div>

            <div id="semanaRelacionada" class="{{ $evento->tipo === 'diario' ? '' : 'hidden' }}">
                <label for="id_evento_padre" class="block font-medium text-gray-700">¿A que semana pertenece?</label>
                <select name="id_evento_padre" class="mt-1 block w-full border border-gray-300 rounded-lg shadow-sm">
                    <option value="">Ninguna semana asociada</option>
                    @foreach($semanales as $semana)
                        <option value="{{ $semana->id }}" {{ $evento->id_evento_padre == $semana->id ? 'selected' : '' }}>
                            {{ $semana->titulo }} - {{ $semana->fecha }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <input type="text" name="titulo" placeholder="Título del evento" value="{{ $evento->titulo }}" required class="w-full p-2 border border-gray-300 rounded-lg">
            </div>

            <div class="relative">
                <input type="text" id="fecha" name="fecha" value="{{ $evento->fecha }}" required placeholder="Seleccione una fecha"
                       class="w-full p-2 pr-10 border border-gray-300 rounded-lg appearance-none">
                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" stroke-width="2"
                         viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M8 7V3M16 7V3M4 11H20M5 19H19A2 2 0 0021 17V7A2 2 0 0019 5H5A2 2 0 003 7V17A2 2 0 005 19Z" />
                    </svg>
                </div>
                <div id="info-fechas-semana" class="text-sm text-gray-600 hidden mt-1"></div>
            </div>

            <div>
                <input type="text" name="lugar" value="{{ $evento->lugar }}" placeholder="Lugar del evento" required class="w-full p-2 border border-gray-300 rounded-lg">
            </div>

            <div id="horariosDiarios" class="{{ $evento->tipo === 'diario' ? '' : 'hidden' }}">
                <label class="block font-medium text-gray-700">Hora de inicio</label>
                <input type="time" name="hora" value="{{ $evento->hora }}" class="w-full p-2 border border-gray-300 rounded-lg mb-2">

                <label class="block font-medium text-gray-700">Hora de término</label>
                <input type="time" name="hora_termino" value="{{ $evento->hora_termino }}" class="w-full p-2 border border-gray-300 rounded-lg">
            </div>

            <div id="editor-menu-bar" class="mb-2"></div>
            <textarea name="descripcion" id="editor" rows="4" class="w-full p-2 border border-gray-300 rounded-lg">{!! $evento->descripcion !!}</textarea>
            <div id="editor-word-count" class="text-sm text-right text-gray-600 mt-1"></div>

            <button type="submit"
                id="guardarBtn"
                class="w-full bg-[#328E6E] hover:bg-[#287256] text-white font-bold py-2 px-4 rounded-lg transition">
                Guardar Cambios
            </button>
        </form>

        <div class="flex justify-center mt-4">
            <img src="{{ asset('img/logo-uda.png') }}" alt="Logo Universidad de Atacama" class="h-12">
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="{{ asset('js/crearEvento.js') }}"></script>
    <script>
      document.addEventListener('DOMContentLoaded', () => {
        const tipoSelect = document.getElementById('tipo');
        const tipoOriginal = '{{ $evento->tipo }}'; // tipo antes de editar
        const guardarBtn = document.getElementById('guardarBtn');
        const form = guardarBtn.closest('form');

        guardarBtn.addEventListener('click', (e) => {
          const tipoActual = tipoSelect.value;

          if (tipoOriginal === 'semanal' && tipoActual === 'diario') {
            e.preventDefault(); // evita envío inmediato

            const confirmar = confirm(
              '⚠️ Estás cambiando este evento de SEMANAL a DIARIO.\n\nEsto eliminará todos los eventos diarios asociados a esta semana.\n\n¿Deseas continuar?'
            );

            if (confirmar) {
              form.submit(); // envía el formulario manualmente
            }
          }
        });
      });
    </script>

</body>
</html>
