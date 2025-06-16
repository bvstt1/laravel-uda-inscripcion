<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Seleccionar Evento</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-white min-h-screen py-12 px-4 font-sans">

  <div class="max-w-7xl mx-auto">
    <div class="flex justify-end space-x-3 mb-6">
        <a href="{{ route('panel') }}"
        class="inline-block px-3 py-1 bg-emerald-100 text-emerald-800 rounded-md hover:bg-emerald-200 transition">
            ← Volver al Panel
        </a>
    </div>

    <!-- Título -->
    <h1 class="text-4xl font-bold text-[#328E6E] mb-10 text-center">🧾 Selecciona un evento para registrar asistencia</h1>

    <!-- Filtros -->
    <form method="GET" class="mb-10 flex flex-wrap justify-center gap-6">
      <div>
        <label for="estado" class="block text-sm font-semibold text-gray-700 mb-1">Estado:</label>
        <select name="estado" id="estado" onchange="this.form.submit()" class="p-2 border border-gray-300 rounded-lg">
          <option value="">Todos</option>
          <option value="activos" {{ request('estado') === 'activos' ? 'selected' : '' }}>Activos</option>
          <option value="bloqueados" {{ request('estado') === 'bloqueados' ? 'selected' : '' }}>Bloqueados</option>
        </select>
      </div>

      <div>
        <label for="categoria" class="block text-sm font-semibold text-gray-700 mb-1">Categoría:</label>
        <select name="categoria" id="categoria" onchange="this.form.submit()" class="p-2 border border-gray-300 rounded-lg">
          <option value="">Todas</option>
          @foreach($categorias as $cat)
            <option value="{{ $cat->id }}" {{ request('categoria') == $cat->id ? 'selected' : '' }}>{{ $cat->nombre }}</option>
          @endforeach
        </select>
      </div>
    </form>

    <!-- Eventos Semanales -->
    <h2 class="text-2xl font-semibold text-[#328E6E] mb-4">📆 Eventos Semanales</h2>
    @forelse($eventosSemanales as $semana)
      <div class="mb-4 border border-gray-300 rounded-lg bg-white">
        <button onclick="toggleSemana({{ $semana->id }})"
          class="w-full text-left bg-gray-100 hover:bg-gray-200 px-4 py-2 font-semibold rounded-t-lg">
          📅 {{ $semana->titulo }} ({{ $semana->fecha }} – {{ \Carbon\Carbon::parse($semana->fecha)->addDays(6)->format('Y-m-d') }})
        </button>

        <div id="dias-{{ $semana->id }}" class="hidden px-4 py-4 space-y-4">
          {{-- Aquí permanece tu lógica exacta para mostrar los días de la semana --}}
          @php
            $eventosOrdenados = $semana->dias
              ->filter(function($e) {
                $estado = request('estado');
                $categoria = request('categoria');
                $bloqueado = false;

                if ($e->hora_termino) {
                  try {
                    $fechaHoraTermino = \Carbon\Carbon::parse($e->fecha . ' ' . $e->hora_termino);
                    $bloqueado = \Carbon\Carbon::now()->greaterThanOrEqualTo($fechaHoraTermino->copy()->subMinutes(15));
                  } catch (\Exception $ex) {
                    $bloqueado = false;
                  }
                }

                if ($estado === 'activos' && $bloqueado) return false;
                if ($estado === 'bloqueados' && !$bloqueado) return false;
                if ($categoria && $e->categoria_id != $categoria) return false;

                return true;
              })
              ->sortBy([
                fn($a, $b) => strcmp($a->fecha, $b->fecha) ?: strcmp($a->hora, $b->hora)
              ]);
          @endphp

          @forelse($eventosOrdenados as $evento)
            @php
              $bloqueado = false;
              if ($evento->hora_termino) {
                try {
                  $fechaHoraTermino = \Carbon\Carbon::parse($evento->fecha . ' ' . $evento->hora_termino);
                  $bloqueado = \Carbon\Carbon::now()->greaterThanOrEqualTo($fechaHoraTermino->copy()->subMinutes(15));
                } catch (\Exception $e) {}
              }
            @endphp

            <div class="border-l-4 border-[#328E6E] bg-white rounded-md p-4 shadow-sm">
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

    <!-- Eventos diarios independientes -->
    <hr class="my-10">
    <h2 class="text-2xl font-semibold text-[#328E6E] mb-4">📌 Eventos Diarios Independientes</h2>
    @if($eventosDiariosIndependientes->isEmpty())
      <p class="text-gray-500 italic">No hay eventos diarios independientes disponibles.</p>
    @else
      @php
        $eventosDiariosOrdenados = $eventosDiariosIndependientes
          ->filter(function($e) {
            $estado = request('estado');
            $categoria = request('categoria');
            $bloqueado = false;

            if ($e->hora_termino) {
              try {
                $fechaHoraTermino = \Carbon\Carbon::parse($e->fecha . ' ' . $e->hora_termino);
                $bloqueado = \Carbon\Carbon::now()->greaterThanOrEqualTo($fechaHoraTermino->copy()->subMinutes(15));
              } catch (\Exception $ex) {
                $bloqueado = false;
              }
            }

            if ($estado === 'activos' && $bloqueado) return false;
            if ($estado === 'bloqueados' && !$bloqueado) return false;
            if ($categoria && $e->categoria_id != $categoria) return false;

            return true;
          })
          ->sortBy([
            fn($a, $b) => strcmp($a->fecha, $b->fecha) ?: strcmp($a->hora, $b->hora)
          ]);
      @endphp

      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($eventosDiariosOrdenados as $evento)
          @php
            $color = $evento->categoria->color ?? '#CBD5E0';
            $nombreCategoria = $evento->categoria->nombre ?? 'Sin categoría';
            $bloqueado = false;
            if ($evento->hora_termino) {
              try {
                $fechaHoraTermino = \Carbon\Carbon::parse($evento->fecha . ' ' . $evento->hora_termino);
                $bloqueado = \Carbon\Carbon::now()->greaterThanOrEqualTo($fechaHoraTermino->copy()->subMinutes(15));
              } catch (\Exception $e) {}
            }
          @endphp

          <div class="bg-white border-l-8 rounded-xl shadow-md p-5 border border-gray-100 transition transform hover:-translate-y-1"
               style="border-left-color: {{ $color }};">
            <h2 class="text-lg font-semibold text-[#328E6E] mb-1">{{ $evento->titulo }}</h2>
            <p class="text-xs font-medium text-gray-500 mb-1">Categoría: {{ $nombreCategoria }}</p>
            <p class="text-sm text-gray-700">
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
