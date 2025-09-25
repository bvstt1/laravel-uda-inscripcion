@php
use Carbon\Carbon;
$ahora = Carbon::now();
$buscar = request('buscar') ?? '';
@endphp

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Panel de Administración de Eventos</title>
  <link rel="stylesheet" href="{{ asset('css/ckeditor-content.css') }}">
  @vite('resources/css/app.css')
  @vite('resources/js/app.js')
</head>
<body class="bg-gray-50 min-h-screen text-gray-800 flex flex-col">

  <!-- Header -->
  <header class="bg-[#007b71] text-gray-100 py-4 shadow-md">
    <div class="max-w-7xl mx-auto px-6 flex flex-col md:flex-row justify-between items-center gap-3">
      <!-- Título -->
      <a class="text-2xl md:text-3xl font-bold">Panel de Administración de Eventos</a>
      <!-- Botones de cuenta -->
      <div class="flex gap-3 text-sm">
        <a href="{{ route('panel') }}" class="inline-block px-3 py-1 bg-emerald-100 text-emerald-800 rounded-md hover:bg-emerald-200 transition">
          ← Volver al panel</a>
        <a href="/logout" class="px-3 py-1 bg-white text-red-600 rounded-lg shadow hover:bg-red-100 transition font-semibold">
          Cerrar sesión
        </a>
      </div>
    </div>
  </header>

  <!-- Mensaje éxito -->
  @if(session('success'))
    <div id="success-message" class="mb-6 p-4 bg-green-100 text-green-800 rounded-md border border-green-300 transition-opacity duration-500">
      {{ session('success') }}
    </div>
    <script>
      setTimeout(() => {
        const msg = document.getElementById('success-message');
        if (msg) { msg.style.opacity = '0'; setTimeout(() => msg.remove(), 500); }
      }, 3000);
    </script>
  @endif

  <!-- Contenido principal -->
  <main class="flex-1 max-w-7xl mx-auto py-10 px-6">

    <!-- Filtros y crear evento -->
    <div class="bg-[#007b71] mb-10 p-6 rounded-2xl shadow-lg flex flex-wrap  items-center text-white">

      <!-- Botón crear evento -->
      <a href="{{ route('eventos.create') }}" 
        class="bg-white text-[#328E6E] font-semibold px-5 py-2 rounded-xl shadow hover:bg-gray-100 transition">
        + Crear nuevo evento
      </a>

      <!-- Formulario de filtros -->
      <form method="GET" class="flex flex-wrap gap-4 items-center flex-1 justify-end">

        <!-- Barra de búsqueda por título -->
        <div class="flex flex-col">
          <label class="text-sm font-semibold text-white mb-1">Buscar por título:</label>
          <input 
            type="text" 
            name="buscar" 
            value="{{ $buscar }}" 
            placeholder="Ingrese el título" 
            class="p-2 rounded-lg border-2 border-gray-300 bg-white text-gray-800 shadow-sm 
                  focus:outline-none transition"
            onkeyup="this.form.submit()"
          >
        </div>

        <!-- Filtro por categoría -->
        <div class="flex flex-col">
          <label class="text-sm font-semibold text-white mb-1">Categoría:</label>
          <select name="categoria_id" onchange="this.form.submit()" 
                  class="p-2 rounded-lg border-2 border-gray-300 bg-white text-gray-800 shadow-sm 
                        cursor-pointer">
            <option value="">Todas</option>
            @foreach($categorias as $cat)
              <option value="{{ $cat->id }}" {{ request('categoria_id') == $cat->id ? 'selected' : '' }}>{{ $cat->nombre }}</option>
            @endforeach
          </select>
        </div>

        <!-- Filtro por estado -->
        <div class="flex flex-col">
          <label class="text-sm font-semibold text-white mb-1">Estado:</label>
          <select name="estado" onchange="this.form.submit()" 
                  class="p-2 rounded-lg border-2 border-gray-300 bg-white text-gray-800 shadow-sm 
                        cursor-pointer">
            <option value="">Todos</option>
            <option value="activos" {{ request('estado') === 'activos' ? 'selected' : '' }}>Activos</option>
            <option value="terminados" {{ request('estado') === 'terminados' ? 'selected' : '' }}>Terminados</option>
          </select>
        </div>
      </form>
    </div>


    <!-- Función para filtrar eventos -->
    @php
      function filtrarEventos($eventos, $ahora, $buscar) {
        $categoriaFiltro = request('categoria_id');
        $estadoFiltro = request('estado');

        return $eventos->filter(function($e) use ($ahora, $categoriaFiltro, $estadoFiltro, $buscar) {
          $fechaEvento = Carbon::parse($e->fecha . ' ' . ($e->hora_termino ?? $e->hora));

          if($estadoFiltro === 'activos' && $fechaEvento->lessThanOrEqualTo($ahora)) return false;
          if($estadoFiltro === 'terminados' && $fechaEvento->greaterThan($ahora)) return false;

          if($categoriaFiltro === 'none' && !empty($e->categoria_id)) return false;
          if(is_numeric($categoriaFiltro) && $e->categoria_id != $categoriaFiltro) return false;

          if($buscar && stripos($e->titulo, $buscar) === false) return false;

          return true;
        });
      }

      $eventosDiariosFiltrados = filtrarEventos($eventosDiarios->sortBy('fecha')->sortBy('hora'), $ahora, $buscar);
      $eventosSemanalesFiltrados = filtrarEventos($eventosSemanales->sortBy('fecha'), $ahora, $buscar);
    @endphp

    <!-- Eventos Diarios -->
    <section class="mb-16">
      <h2 class="text-2xl font-semibold text-gray-800 mb-4">Eventos Diarios</h2>
      <div class="grid sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($eventosDiariosFiltrados as $evento)
          @php
            $color = $evento->categoria->color ?? '#CBD5E0';
            $nombreCategoria = $evento->categoria->nombre ?? 'Sin categoría';
          @endphp
          <div class="bg-white border-l-8 rounded-xl shadow-md hover:shadow-lg p-6 border border-gray-100 transition relative" style="border-color: {{ $color }}">
            <h3 class="text-lg font-semibold text-[#328E6E]">{{ $evento->titulo }}</h3>
            <p class="text-xs font-semibold text-gray-500 mt-1">Categoría: {{ $nombreCategoria }}</p>
            <p class="text-sm text-gray-700 mt-1"><strong>Fecha:</strong> {{ $evento->fecha }}</p>
            <p class="text-sm text-gray-700"><strong>Horario:</strong> {{ Carbon::parse($evento->hora)->format('H:i') }} @if($evento->hora_termino) - {{ Carbon::parse($evento->hora_termino)->format('H:i') }} @endif</p>
            <p class="text-sm text-gray-700"><strong>Lugar:</strong> {{ $evento->lugar }}</p>
            <div class="mt-4 flex gap-2">
              <a href="{{ route('eventos.edit', $evento->id) }}" class="text-sm bg-blue-500 hover:bg-blue-600 text-white px-4 py-1 rounded-lg">Editar</a>
              <form action="{{ route('eventos.destroy', $evento->id) }}" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar este evento?')">
                @csrf @method('DELETE')
                <button type="submit" class="text-sm bg-red-500 hover:bg-red-600 text-white px-4 py-1 rounded-lg cursor-pointer">Eliminar</button>
              </form>
              <button class="ver-mas-btn text-sm text-emerald-600 hover:underline ml-auto cursor-pointer" data-id="{{ $evento->id }}">Ver más</button>
            </div>
          </div>

          <!-- Modal evento diario -->
          <div id="modal-{{ $evento->id }}" class="fixed inset-0 hidden justify-center items-center z-50">
            <div class="absolute inset-0  backdrop-blur-sm" data-modal-close></div>
            <div class="relative bg-white rounded-lg max-w-xl w-full p-6 shadow-xl overflow-y-auto max-h-[90vh]">
              <h2 class="text-2xl font-bold text-[#328E6E] mb-4">{{ $evento->titulo }}</h2>
              <p class="text-sm text-gray-600 mb-1"><strong>Fecha:</strong> {{ $evento->fecha }}</p>
              <p class="text-sm text-gray-600 mb-1"><strong>Horario:</strong> {{ Carbon::parse($evento->hora)->format('H:i') }} @if($evento->hora_termino) - {{ Carbon::parse($evento->hora_termino)->format('H:i') }} @endif</p>
              <p class="text-sm text-gray-600 mb-4"><strong>Lugar:</strong> {{ $evento->lugar }}</p>
              <div class="prose prose-sm max-w-none text-gray-700">{!! $evento->descripcion_html ?? $evento->descripcion !!}</div>
              <button class="cerrar-modal-btn absolute top-2 right-4 text-gray-500 hover:text-gray-700 text-xl" data-modal-close>&times;</button>
            </div>
          </div>
        @empty
          <p class="text-gray-500 italic">No hay eventos diarios que coincidan con los filtros seleccionados.</p>
        @endforelse
      </div>
    </section>

    <!-- Eventos Semanales -->
    <section>
      <h2 class="text-2xl font-semibold text-gray-800 mb-4">Eventos Semanales</h2>
      <div class="grid sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($eventosSemanalesFiltrados as $evento)
          @php
            $color = $evento->categoria->color ?? '#CBD5E0';
            $nombreCategoria = $evento->categoria->nombre ?? 'Sin categoría';
            $inicioSemana = Carbon::parse($evento->fecha)->startOfWeek();
            $finSemana = Carbon::parse($evento->fecha)->endOfWeek();
          @endphp
          <div class="bg-white border-l-8 rounded-xl shadow-md hover:shadow-lg p-6 border border-gray-100 transition relative" style="border-color: {{ $color }}">
            <h3 class="text-lg font-semibold text-[#328E6E]">{{ $evento->titulo }}</h3>
            <p class="text-xs font-semibold text-gray-500 mt-1">Categoría: {{ $nombreCategoria }}</p>
            <p class="text-sm text-gray-700 mt-1"><strong>Semana:</strong> {{ $inicioSemana->format('Y-m-d') }} al {{ $finSemana->format('Y-m-d') }}</p>
            <p class="text-sm text-gray-700"><strong>Lugar:</strong> {{ $evento->lugar }}</p>
            <div class="mt-4 flex gap-2">
              <a href="{{ route('eventos.edit', $evento->id) }}" class="text-sm bg-blue-500 hover:bg-blue-600 text-white px-4 py-1 rounded-lg">Editar</a>
              <a href="{{ route('eventos.semanal.dias', $evento->id) }}" class="text-sm bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-1 rounded-lg ">Ver días</a>
              <form action="{{ route('eventos.destroy', $evento->id) }}" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar este evento?')">
                @csrf @method('DELETE')
                <button type="submit" class="text-sm bg-red-500 hover:bg-red-600 text-white px-4 py-1 rounded-lg cursor-pointer">Eliminar</button>
              </form>
              <button class="ver-mas-btn text-sm text-emerald-600 hover:underline ml-auto cursor-pointer" data-id="{{ $evento->id }}">Ver más</button>
            </div>
          </div>

          <!-- Modal evento semanal -->
          <div id="modal-{{ $evento->id }}" class="fixed inset-0 hidden justify-center items-center z-50">
            <div class="absolute inset-0 bg-black bg-opacity-50" data-modal-close></div>
            <div class="relative bg-white rounded-lg max-w-xl w-full p-6 shadow-xl overflow-y-auto max-h-[90vh]">
              <h2 class="text-2xl font-bold text-[#328E6E] mb-4">{{ $evento->titulo }}</h2>
              <p class="text-sm text-gray-600 mb-1"><strong>Semana:</strong> {{ $inicioSemana->format('Y-m-d') }} al {{ $finSemana->format('Y-m-d') }}</p>
              <p class="text-sm text-gray-600 mb-4"><strong>Lugar:</strong> {{ $evento->lugar }}</p>
              <div class="prose prose-sm max-w-none text-gray-700">{!! $evento->descripcion_html ?? $evento->descripcion !!}</div>
              <button class="cerrar-modal-btn absolute top-2 right-4 text-gray-500 hover:text-gray-700 text-xl" data-modal-close>&times;</button>
            </div>
          </div>
        @empty
          <p class="text-gray-500 italic">No hay eventos semanales que coincidan con los filtros seleccionados.</p>
        @endforelse
      </div>
    </section>
  </main>

  <!-- Footer -->
  <footer class="bg-gray-900 text-gray-300 py-10 mt-16">
    <div class="max-w-7xl mx-auto px-6 flex flex-col md:flex-row justify-between items-center gap-6">
      <!-- Redes sociales -->
      <div class="flex items-center space-x-4">
        <a href="https://www.facebook.com/UDAinstitucional" target="_blank" class="hover:text-white transition">
          <i class="fa-brands fa-square-facebook text-2xl"></i>
        </a>
        <a href="https://x.com/UAtacama" target="_blank" class="hover:text-white transition">
          <i class="fa-brands fa-twitter text-2xl"></i>
        </a>
        <a href="https://www.instagram.com/u_atacama/" target="_blank" class="hover:text-white transition">
          <i class="fa-brands fa-instagram text-2xl"></i>
        </a>
        <a href="https://www.linkedin.com/company/uda-universidad-de-atacama/" target="_blank" class="hover:text-white transition">
          <i class="fa-brands fa-linkedin text-2xl"></i>
        </a>
        <a href="https://www.youtube.com/c/UDATelevisi%C3%B3n" target="_blank" class="hover:text-white transition">
          <i class="fa-brands fa-youtube text-2xl"></i>
        </a>
      </div>
      <!-- Crédito -->
      <div class="text-sm text-gray-400 text-center md:text-right">
        &copy; {{ date('Y') }} Universidad de Atacama.
      </div>
    </div>
  </footer>

  <!-- Scripts -->
  <script src="https://kit.fontawesome.com/782e1f1389.js" crossorigin="anonymous"></script>
  <script src="{{ asset('js/verMas.js') }}"></script>

</body>
</html>
