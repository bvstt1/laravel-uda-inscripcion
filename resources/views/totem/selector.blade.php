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
  <title>Seleccionar Evento</title>
  @vite('resources/css/app.css')
  @vite('resources/js/app.js')
</head>
<body class="bg-gray-50 min-h-screen text-gray-800 flex flex-col">

  <!-- Header -->
  <header class="bg-[#007b71] text-gray-100 py-4 shadow-md">
    <div class="max-w-7xl mx-auto px-6 flex flex-col md:flex-row justify-between items-center gap-3">
      <!-- Título -->
      <a class="text-2xl md:text-3xl font-bold">Totem de registro de asistencia a Eventos</a>
      <!-- Botones de cuenta -->
      <div class="flex gap-3 text-sm">
        <a href="{{ route('panel') }}" class="inline-block px-3 py-1 bg-emerald-100 text-emerald-800 rounded-md hover:bg-emerald-200 transition">
          ← Volver al panel</a>
        <a href="{{ route('logout') }}" class="px-3 py-1 bg-white text-red-600 rounded-lg shadow hover:bg-red-100 transition font-semibold">
          Cerrar sesión
        </a>
      </div>
    </div>
  </header>

  <div class="max-w-7xl mx-auto mt-10 flex-1">

    <div class="max-w-7xl mx-auto px-4">

      <!-- Filtros y búsqueda -->
      <form method="GET" class="mb-10 flex flex-col md:flex-row items-center justify-end gap-6">

        <!-- Filtro Estado -->
        <div class="flex flex-col">
          <label for="estado" class="text-sm font-semibold text-gray-700 mb-1">Estado:</label>
          <select name="estado" id="estado" onchange="this.form.submit()" class="p-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-[#328E6E] focus:border-[#328E6E] transition">
            <option value="">Todos</option>
            <option value="activos" {{ request('estado') === 'activos' ? 'selected' : '' }}>Activos</option>
            <option value="bloqueados" {{ request('estado') === 'bloqueados' ? 'selected' : '' }}>Bloqueados</option>
          </select>
        </div>

        <!-- Filtro Categoría -->
        <div class="flex flex-col">
          <label for="categoria" class="text-sm font-semibold text-gray-700 mb-1">Categoría:</label>
          <select name="categoria" id="categoria" onchange="this.form.submit()" class="p-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-[#328E6E] focus:border-[#328E6E] transition">
            <option value="">Todas</option>
            @foreach($categorias as $cat)
              <option value="{{ $cat->id }}" {{ request('categoria') == $cat->id ? 'selected' : '' }}>{{ $cat->nombre }}</option>
            @endforeach
          </select>
        </div>
      </form>
    </div>

    <!-- Eventos Semanales -->
    <h2 class="text-2xl font-semibold text-[#328E6E] mb-4">Eventos Semanales</h2>
    @forelse($eventosSemanales as $semana)
      <div class="mb-4 border border-gray-300 rounded-lg bg-white">
        <button onclick="toggleSemana({{ $semana->id }})"
          class="w-full text-left bg-gray-100 hover:bg-gray-200 px-4 py-2 font-semibold rounded-t-lg">
          📅 {{ $semana->titulo }} ({{ $semana->fecha }} – {{ \Carbon\Carbon::parse($semana->fecha)->addDays(6)->format('Y-m-d') }})
        </button>

        <div id="dias-{{ $semana->id }}" class="hidden px-4 py-4 space-y-4">
          @php
            $eventosOrdenados = $semana->dias
              ->filter(function($e) {
                $estado = request('estado');
                $categoria = request('categoria');
                $bloqueado = false;
                if ($e->hora_termino) {
                  try { $fechaHoraTermino = \Carbon\Carbon::parse($e->fecha . ' ' . $e->hora_termino);
                        $bloqueado = \Carbon\Carbon::now()->greaterThanOrEqualTo($fechaHoraTermino->copy()->subMinutes(15));
                  } catch (\Exception $ex) { $bloqueado = false; }
                }
                if ($estado === 'activos' && $bloqueado) return false;
                if ($estado === 'bloqueados' && !$bloqueado) return false;
                if ($categoria && $e->categoria_id != $categoria) return false;
                return true;
              })
              ->sortBy([fn($a, $b) => strcmp($a->fecha, $b->fecha) ?: strcmp($a->hora, $b->hora)]);
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
    <h2 class="text-2xl font-semibold text-[#328E6E] mb-4">Eventos Diarios Independientes</h2>
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
              try { $fechaHoraTermino = \Carbon\Carbon::parse($e->fecha . ' ' . $e->hora_termino);
                    $bloqueado = \Carbon\Carbon::now()->greaterThanOrEqualTo($fechaHoraTermino->copy()->subMinutes(15));
              } catch (\Exception $ex) { $bloqueado = false; }
            }
            if ($estado === 'activos' && $bloqueado) return false;
            if ($estado === 'bloqueados' && !$bloqueado) return false;
            if ($categoria && $e->categoria_id != $categoria) return false;
            return true;
          })
          ->sortBy([fn($a, $b) => strcmp($a->fecha, $b->fecha) ?: strcmp($a->hora, $b->hora)]);
      @endphp

      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($eventosDiariosOrdenados as $evento)
          @php
            $color = $evento->categoria->color ?? '#CBD5E0';
            $nombreCategoria = $evento->categoria->nombre ?? 'Sin categoría';
            $bloqueado = false;
            if ($evento->hora_termino) {
              try { $fechaHoraTermino = \Carbon\Carbon::parse($evento->fecha . ' ' . $evento->hora_termino);
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
