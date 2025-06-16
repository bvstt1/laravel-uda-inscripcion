@php
  use Carbon\Carbon;
  $ahora = Carbon::now();
@endphp

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Panel de Administración de Eventos</title>
  <link rel="stylesheet" href="{{ asset('css/ckeditor-content.css') }}">
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen font-sans">
  <div class="max-w-7xl mx-auto py-12 px-6">
    <div class="flex flex-col md:flex-row justify-between items-center mb-10 gap-4">
      <h1 class="text-4xl font-bold text-[#328E6E]">Panel de Administración</h1>
      <div class="flex gap-4 text-sm">
          <a href="{{ route('panel') }}"
            class="inline-block px-3 py-1 bg-emerald-100 text-emerald-800 rounded-md hover:bg-emerald-200 transition">
            ← Volver al panel
          </a>
          <a href="/logout"
            class="inline-block px-3 py-1 bg-red-100 text-red-700 rounded-md hover:bg-red-200 transition">
            🔓 Cerrar sesión
          </a>
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

    <!-- Filtros -->
    <div class="mb-10 flex flex-wrap gap-4 items-center">
      <a href="{{ route('eventos.create') }}" class="bg-[#328E6E] hover:bg-[#287256] text-white py-2 px-6 rounded-lg shadow-md transition">
        + Crear nuevo evento
      </a>

      <form method="GET" class="flex flex-wrap gap-4 items-center">
        <div>
          <label class="text-sm font-medium text-gray-700">Categoría:</label>
          <select name="categoria_id" onchange="this.form.submit()" class="p-2 border rounded-lg">
            <option value="">Todas</option>
            @foreach($categorias as $cat)
              <option value="{{ $cat->id }}" {{ request('categoria_id') == $cat->id ? 'selected' : '' }}>{{ $cat->nombre }}</option>
            @endforeach
          </select>
        </div>

        <div>
          <label class="text-sm font-medium text-gray-700">Estado:</label>
          <select name="estado" onchange="this.form.submit()" class="p-2 border rounded-lg">
            <option value="">Todos</option>
            <option value="activos" {{ request('estado') === 'activos' ? 'selected' : '' }}>Activos</option>
            <option value="terminados" {{ request('estado') === 'terminados' ? 'selected' : '' }}>Terminados</option>
          </select>
        </div>
      </form>
    </div>

    <!-- Eventos Diarios -->
    <section class="mb-16">
      <h2 class="text-2xl font-semibold text-gray-800 mb-4">📅 Eventos Diarios</h2>
      <div class="grid sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @php
          $eventosFiltrados = $eventosDiarios->sortBy('fecha')->sortBy('hora')->filter(function($e) use ($ahora) {
            $categoriaFiltro = request('categoria_id');
            $fechaEvento = Carbon::parse($e->fecha . ' ' . ($e->hora_termino ?? $e->hora));

            if (request('estado') === 'activos' && $fechaEvento->lessThanOrEqualTo($ahora)) return false;
            if (request('estado') === 'terminados' && $fechaEvento->greaterThan($ahora)) return false;

            if ($categoriaFiltro === 'none' && !empty($e->categoria_id)) return false;
            if (is_numeric($categoriaFiltro) && $e->categoria_id != $categoriaFiltro) return false;

            return true;
          });
        @endphp

        @forelse($eventosFiltrados as $evento)
          @php
            $color = $evento->categoria->color ?? '#CBD5E0';
            $nombreCategoria = $evento->categoria->nombre ?? 'Sin categoría';
          @endphp
          <div class="bg-white border-l-8 rounded-xl shadow-md hover:shadow-lg p-6 border border-gray-100 transition relative" style="border-color: {{ $color }};">
            <h3 class="text-lg font-semibold text-[#328E6E]">{{ $evento->titulo }}</h3>
            <p class="text-xs font-semibold text-gray-500 mt-1">Categoría: {{ $nombreCategoria }}</p>
            <p class="text-sm text-gray-700 mt-1"><strong>Fecha:</strong> {{ $evento->fecha }}</p>
            <p class="text-sm text-gray-700"><strong>Horario:</strong> {{ Carbon::parse($evento->hora)->format('H:i') }} @if($evento->hora_termino) - {{ Carbon::parse($evento->hora_termino)->format('H:i') }} @endif</p>
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
          <!-- Modal para evento diario -->
          <div id="modal-{{ $evento->id }}" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden justify-center items-center">
            <div class="bg-white rounded-lg max-w-xl w-full p-6 shadow-xl overflow-y-auto max-h-[90vh] relative">
              <h2 class="text-2xl font-bold text-[#328E6E] mb-4">{{ $evento->titulo }}</h2>
              <p class="text-sm text-gray-600 mb-1"><strong>Fecha:</strong> {{ $evento->fecha }}</p>
              <p class="text-sm text-gray-600 mb-1"><strong>Horario:</strong> {{ Carbon::parse($evento->hora)->format('H:i') }} @if($evento->hora_termino) - {{ Carbon::parse($evento->hora_termino)->format('H:i') }} @endif</p>
              <p class="text-sm text-gray-600 mb-4"><strong>Lugar:</strong> {{ $evento->lugar }}</p>
              <div class="prose prose-sm max-w-none text-gray-700"><strong>Descripción:</strong>{!! $evento->descripcion_html ?? $evento->descripcion !!}</div>
              <button class="cerrar-modal-btn absolute top-2 right-4 text-gray-500 hover:text-gray-700 text-xl" data-id="{{ $evento->id }}">&times;</button>
            </div>
          </div>

        @empty
          <p class="text-gray-500 italic">No hay eventos que coincidan con los filtros seleccionados.</p>
        @endforelse
      </div>
    </section>

    <!-- Eventos Semanales -->
    <section>
      <h2 class="text-2xl font-semibold text-gray-800 mb-4">🗓️ Eventos Semanales</h2>
      <div class="grid sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @php
          $eventosFiltrados = $eventosSemanales->sortBy('fecha')->filter(function($e) use ($ahora) {
            $categoriaFiltro = request('categoria_id');
            $fechaEvento = Carbon::parse($e->fecha)->endOfWeek();

            if (request('estado') === 'activos' && $fechaEvento->lessThanOrEqualTo($ahora)) return false;
            if (request('estado') === 'terminados' && $fechaEvento->greaterThan($ahora)) return false;

            if ($categoriaFiltro === 'none' && !empty($e->categoria_id)) return false;
            if (is_numeric($categoriaFiltro) && $e->categoria_id != $categoriaFiltro) return false;

            return true;
          });
        @endphp

        @forelse($eventosFiltrados as $evento)
          @php
            $color = $evento->categoria->color ?? '#CBD5E0';
            $nombreCategoria = $evento->categoria->nombre ?? 'Sin categoría';
            $inicioSemana = Carbon::parse($evento->fecha)->startOfWeek();
            $finSemana = Carbon::parse($evento->fecha)->endOfWeek();
          @endphp
          <div class="bg-white border-l-8 rounded-xl shadow-md hover:shadow-lg p-6 border border-gray-100 transition" style="border-color: {{ $color }};">
            <h3 class="text-lg font-semibold text-[#328E6E]">{{ $evento->titulo }}</h3>
            <p class="text-xs font-semibold text-gray-500 mt-1">Categoría: {{ $nombreCategoria }}</p>
            <p class="text-sm text-gray-700 mt-1"><strong>Semana:</strong> {{ $inicioSemana->format('Y-m-d') }} al {{ $finSemana->format('Y-m-d') }}</p>
            <p class="text-sm text-gray-700"><strong>Lugar:</strong> {{ $evento->lugar }}</p>
            <div class="mt-4 flex gap-2">
              <a href="{{ route('eventos.edit', $evento->id) }}" class="text-sm bg-blue-500 hover:bg-blue-600 text-white px-4 py-1 rounded-lg">Editar</a>
              <a href="{{ route('eventos.semanal.dias', $evento->id) }}" class="text-sm bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-1 rounded-lg">
                Ver días
              </a>
              <form action="{{ route('eventos.destroy', $evento->id) }}" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar este evento?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="text-sm bg-red-500 hover:bg-red-600 text-white px-4 py-1 rounded-lg">Eliminar</button>
              </form>
              <button class="ver-mas-btn text-sm text-emerald-600 hover:underline ml-auto" data-id="{{ $evento->id }}">Ver más</button>
            </div>
          </div>
          <!-- Modal para evento semanal -->
          <div id="modal-{{ $evento->id }}" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden justify-center items-center">
            <div class="bg-white rounded-lg max-w-xl w-full p-6 shadow-xl overflow-y-auto max-h-[90vh] relative">
              <h2 class="text-2xl font-bold text-[#328E6E] mb-4">{{ $evento->titulo }}</h2>
              <p class="text-sm text-gray-600 mb-1"><strong>Semana:</strong> {{ $inicioSemana->format('Y-m-d') }} al {{ $finSemana->format('Y-m-d') }}</p>
              <p class="text-sm text-gray-600 mb-1"><strong>Lugar:</strong> {{ $evento->lugar }}</p>
              <div class="prose prose-sm max-w-none text-gray-700"><strong>Descripción:</strong>{!! $evento->descripcion_html ?? $evento->descripcion !!}</div>
              <button class="cerrar-modal-btn absolute top-2 right-4 text-gray-500 hover:text-gray-700 text-xl" data-id="{{ $evento->id }}">&times;</button>
            </div>
          </div>
        @empty
          <p class="text-gray-500 italic">No hay eventos que coincidan con los filtros seleccionados.</p>
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
