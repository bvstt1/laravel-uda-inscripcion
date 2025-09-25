@php
use Carbon\Carbon;
$ahora = Carbon::now();
$buscar = request('buscar') ?? '';
@endphp

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Inscripciones por Evento</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  @vite('resources/css/app.css')
  @vite('resources/js/app.js')
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
  <style>
    body { font-family: 'Inter', sans-serif; }
  </style>
</head>
<body class="bg-gray-50 min-h-screen text-gray-800 flex flex-col">

  <!-- Header -->
  <header class="bg-[#007b71] text-gray-100 py-4 shadow-md">
    <div class="max-w-7xl mx-auto px-6 flex flex-col md:flex-row justify-between items-center gap-3">
      <a class="text-2xl md:text-3xl font-bold">Gestión de Inscripciones</a>
      <div class="flex gap-3 text-sm">
        <a href="{{ route('panel') }}" class="inline-block px-3 py-1 bg-emerald-100 text-emerald-800 rounded-md hover:bg-emerald-200 transition">← Volver al panel</a>
        <a href="{{ route('logout') }}" class="px-3 py-1 bg-white text-red-600 rounded-lg shadow hover:bg-red-100 transition font-semibold">Cerrar sesión</a>
      </div>
    </div>
  </header>

  <!-- Contenido principal -->
  <main class=" flex-1 mx-auto py-10 px-6 w-full max-w-[1400px]">
    <!-- Filtros y crear evento -->
    <div class="bg-[#007b71] mb-10 p-6 rounded-2xl shadow-lg flex flex-wrap items-end gap-6 text-white">

      <!-- Formulario de filtros -->
      <form method="GET" class="flex flex-wrap gap-6 w-full items-end">

        <!-- Barra de búsqueda por título -->
        <div class="flex flex-col flex-1 min-w-[220px]">
          <label class="text-sm font-semibold text-white mb-1">Buscar por título:</label>
          <input 
            type="text" 
            name="buscar" 
            value="{{ $buscar ?? '' }}" 
            placeholder="Ingrese el título" 
            class="p-2 rounded-lg border-2 border-gray-300 bg-white text-gray-800 shadow-sm 
                  focus:outline-none focus:ring-2 focus:ring-white transition"
            onkeyup="this.form.submit()"
          >
        </div>

        <!-- Filtro por categoría -->
        <div class="flex flex-col min-w-[180px]">
          <label class="text-sm font-semibold text-white mb-1">Categoría:</label>
          <select name="categoria_id" onchange="this.form.submit()" 
                  class="p-2 rounded-lg border-2 border-gray-300 bg-white text-gray-800 shadow-sm cursor-pointer transition">
            <option value="">Todas</option>
            @foreach($categorias as $cat)
              <option value="{{ $cat->id }}" {{ request('categoria_id') == $cat->id ? 'selected' : '' }}>{{ $cat->nombre }}</option>
            @endforeach
          </select>
        </div>

        <!-- Filtro por estado -->
        <div class="flex flex-col min-w-[160px]">
          <label class="text-sm font-semibold text-white mb-1">Estado:</label>
          <select name="estado" onchange="this.form.submit()" 
                  class="p-2 rounded-lg border-2 border-gray-300 bg-white text-gray-800 shadow-sm cursor-pointer transition">
            <option value="">Todos</option>
            <option value="activos" {{ request('estado') === 'activos' ? 'selected' : '' }}>Activos</option>
            <option value="terminados" {{ request('estado') === 'terminados' ? 'selected' : '' }}>Terminados</option>
          </select>
        </div>

      </form>
    </div>


    <!-- EVENTOS DIARIOS AISLADOS -->
    <section class="mb-12">
      <h2 class="text-2xl font-semibold text-gray-800 mb-4">Eventos Diarios Aislados</h2>
      @if($eventosDiariosAislados->isEmpty())
        <p class="text-gray-500 italic">No hay eventos diarios aislados disponibles.</p>
      @else
        <div class="grid sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          @foreach($eventosDiariosAislados->sortBy('fecha') as $evento)
            @php
              $color = $evento->categoria->color ?? '#CBD5E0';
              $nombreCategoria = $evento->categoria->nombre ?? 'Sin categoría';
            @endphp
            <a href="{{ route('admin.inscritos.evento', $evento->id) }}"
               class="bg-white border-l-8 rounded-xl shadow-md hover:shadow-lg p-5 border border-gray-100 transition transform hover:-translate-y-1"
               style="border-left-color: {{ $color }};">
              <h3 class="text-lg font-semibold text-[#328E6E]">{{ $evento->titulo }}</h3>
              <p class="text-xs font-medium text-gray-500">Categoría: {{ $nombreCategoria }}</p>
              <p class="text-sm text-gray-700 mt-1"><strong>Fecha:</strong> {{ $evento->fecha }}</p>
            </a>
          @endforeach
        </div>
      @endif
    </section>

    <!-- EVENTOS SEMANALES -->
    <section>
      <h2 class="text-2xl font-semibold text-gray-800 mb-4">Eventos Semanales</h2>
      @if($eventosSemanales->isEmpty())
        <p class="text-gray-500 italic">No hay eventos semanales disponibles.</p>
      @else
        <div class="grid sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          @foreach($eventosSemanales->sortBy('fecha') as $evento)
            @php
              $color = $evento->categoria->color ?? '#CBD5E0';
              $nombreCategoria = $evento->categoria->nombre ?? 'Sin categoría';
            @endphp
            <a href="{{ route('admin.inscritos.semana', $evento->id) }}"
               class="bg-white border-l-8 rounded-xl shadow-md hover:shadow-lg p-5 border border-gray-100 transition transform hover:-translate-y-1"
               style="border-left-color: {{ $color }};">
              <h3 class="text-lg font-semibold text-[#328E6E]">{{ $evento->titulo }}</h3>
              <p class="text-xs font-medium text-gray-500">Categoría: {{ $nombreCategoria }}</p>
              <p class="text-sm text-gray-700 mt-1"><strong>Semana del:</strong> {{ $evento->fecha }}</p>
            </a>
          @endforeach
        </div>
      @endif
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
</body>
</html>
