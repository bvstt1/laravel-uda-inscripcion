@php use Carbon\Carbon; @endphp
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Inscripciones por Evento</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans min-h-screen py-10 px-6">

  <div class="max-w-7xl mx-auto">
    <!-- Encabezado -->
    <div class="flex flex-col md:flex-row justify-between items-center mb-10 gap-4">
      <h1 class="text-4xl font-bold text-[#328E6E]">📋 Gestión de Inscripciones</h1>
      <div class="flex gap-4 text-sm">
        <a href="{{ route('panel') }}" class="inline-block px-3 py-1 bg-emerald-100 text-emerald-800 rounded-md hover:bg-emerald-200 transition">&larr; Volver al panel</a>
        <a href="/logout" class="inline-block px-3 py-1 bg-red-100 text-red-700 rounded-md hover:bg-red-200 transition">Cerrar sesión</a>
      </div>
    </div>

    <!-- FILTRO POR CATEGORÍA -->
    <form method="GET" class="mb-6 flex flex-col md:flex-row gap-4 items-start md:items-center">
      <div class="flex gap-2 items-center">
        <label class="text-sm font-medium text-gray-700">Filtrar por categoría:</label>
        <select name="categoria_id" onchange="this.form.submit()" class="p-2 border rounded-lg">
          <option value="">Todas</option>
          @foreach($categorias as $cat)
            <option value="{{ $cat->id }}" {{ request('categoria_id') == $cat->id ? 'selected' : '' }}>
              {{ $cat->nombre }}
            </option>
          @endforeach
        </select>
      </div>

      <div class="flex gap-2 items-center">
        <label class="text-sm font-medium text-gray-700">Filtrar por estado:</label>
        <select name="estado" onchange="this.form.submit()" class="p-2 border rounded-lg">
          <option value="">Todos</option>
          <option value="activo" {{ request('estado') === 'activo' ? 'selected' : '' }}>Activos</option>
          <option value="pasado" {{ request('estado') === 'pasado' ? 'selected' : '' }}>Pasados</option>
        </select>
      </div>
    </form>

    <!-- EVENTOS DIARIOS AISLADOS -->
    <section class="mb-12">
      <h2 class="text-2xl font-semibold text-gray-800 mb-4">📅 Eventos Diarios Aislados</h2>

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
      <h2 class="text-2xl font-semibold text-gray-800 mb-4">🗓️ Eventos Semanales</h2>

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
  </div>
</body>
</html>
