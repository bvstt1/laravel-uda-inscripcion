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
  <title>Eventos Disponibles</title>
  <link rel="stylesheet" href="{{ asset('css/ckeditor-content.css') }}">
  @vite('resources/css/app.css')
  @vite('resources/js/app.js')
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap">
  <style>
    body { font-family: 'Inter', sans-serif; }
  </style>
</head>
<body class="bg-gray-50 min-h-screen text-gray-800 flex flex-col">

  <!-- Header -->
  <header class="bg-[#007b71] text-gray-100 py-4 shadow-md">
    <div class="max-w-7xl mx-auto px-6 flex flex-col md:flex-row justify-between items-center gap-3">
      <!-- Título -->
      <a class="text-2xl md:text-3xl font-bold">Eventos Disponibles</a>

      <!-- Botones de cuenta -->
      <div class="flex gap-3 text-sm">
        <a href="{{ route('cuenta.formulario') }}"
           class="px-3 py-1 bg-white text-[#2D6A4F] rounded-lg shadow hover:bg-gray-100 transition font-semibold">
          Mi cuenta
        </a>
        <a href="/logout"
           class="px-3 py-1 bg-white text-red-600 rounded-lg shadow hover:bg-red-100 transition font-semibold">
          Cerrar sesión
        </a>
      </div>
    </div>
  </header>

  <!-- Contenido principal -->
  <main class="flex-1 max-w-7xl mx-auto py-10 px-6">

  @if(session('success'))
    <div id="success-message" class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-5">
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

  <!-- Panel de filtros -->
  <div class="bg-[#007b71] rounded-xl p-6 mb-8 shadow-md flex flex-wrap gap-6 items-end">
    <form method="GET" class="flex flex-wrap gap-4 items-center w-full">

      <!-- Barra de búsqueda por título -->
      <div class="flex flex-col flex-1 min-w-[200px]">
        <label class="text-sm font-semibold text-white mb-1">Buscar por título:</label>
        <input 
          type="text" 
          name="buscar" 
          value="{{ $buscar }}" 
          placeholder="Ingrese el título" 
          class="p-2 rounded-lg border-2 border-gray-300 bg-white text-gray-800 shadow-sm 
                focus:outline-none transition w-full"
          onkeyup="this.form.submit()"
        >
      </div>

      <!-- Filtro por categoría -->
      <div class="flex flex-col min-w-[150px]">
        <label class="text-sm font-semibold text-white mb-1">Categoría:</label>
        <select name="categoria_id" onchange="this.form.submit()" 
                class="p-2 rounded-lg border-2 border-gray-300 bg-white text-gray-800 shadow-sm cursor-pointer w-full">
          <option value="">Todas</option>
          @foreach($categorias as $cat)
            <option value="{{ $cat->id }}" {{ request('categoria_id') == $cat->id ? 'selected' : '' }}>
              {{ $cat->nombre }}
            </option>
          @endforeach
        </select>
      </div>

      <!-- Filtro por estado -->
      <div class="flex flex-col min-w-[150px]">
        <label class="text-sm font-semibold text-white mb-1">Estado:</label>
        <select name="estado" onchange="this.form.submit()" 
                class="p-2 rounded-lg border-2 border-gray-300 bg-white text-gray-800 shadow-sm cursor-pointer w-full">
          <option value="">Todos</option>
          <option value="activos" {{ request('estado') === 'activos' ? 'selected' : '' }}>Activos</option>
          <option value="terminados" {{ request('estado') === 'terminados' ? 'selected' : '' }}>Terminados</option>
        </select>
      </div>

    </form>
  </div>

    <!-- Eventos Diarios -->
    <section class="mb-16">
      <h2 class="text-2xl font-semibold mb-4 text-[#2D6A4F]">Eventos Diarios</h2>
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($eventosDiarios as $evento)
          @php
            $color = $evento->categoria->color ?? '#CBD5E0';
            $nombreCategoria = $evento->categoria->nombre ?? 'Sin categoría';
            $estado = $inscripciones[$evento->id]->estado ?? null;
          @endphp
          <div class="bg-white border-l-8 rounded-xl shadow-md hover:shadow-lg p-6 border border-gray-100 transition relative"
               style="border-left-color: {{ $color }};">
            <h3 class="text-lg font-semibold text-[#328E6E]">{{ $evento->titulo }}</h3>
            <p class="text-xs font-semibold text-gray-500 mt-1">Categoría: {{ $nombreCategoria }}</p>
            <p class="text-sm text-gray-700 mt-1"><strong>Fecha:</strong> {{ $evento->fecha }}</p>
            <p class="text-sm text-gray-700">
              <strong>Horario:</strong> {{ Carbon::parse($evento->hora)->format('H:i') }}
              @if($evento->hora_termino) - {{ Carbon::parse($evento->hora_termino)->format('H:i') }} @endif
            </p>
            <p class="text-sm text-gray-700"><strong>Lugar:</strong> {{ $evento->lugar }}</p>

            <button class="ver-mas-btn text-sm text-[#328E6E] hover:underline mt-2" data-id="{{ $evento->id }}">
              Ver más
            </button>

            <div class="mt-4">
              @if ($estado === 'inscrito')
                <form method="POST" action="{{ route('desinscribirse', $evento->id) }}" onsubmit="return confirm('¿Estás seguro que deseas desinscribirte?')">
                  @csrf @method('DELETE')
                  <p class="text-xs text-red-500 italic mb-2">⚠️ Esta acción es irreversible.</p>
                  <button type="submit" class="w-full bg-red-500 hover:bg-red-600 text-white py-2 px-4 rounded-xl transition">Desinscribirse</button>
                </form>
              @elseif ($estado === 'desinscrito')
                <div class="text-sm text-gray-500 italic mt-2">Ya te desinscribiste.</div>
              @else
                <form method="POST" action="{{ route('inscribirse', $evento->id) }}" onsubmit="return confirm('¿Deseas inscribirte?')">
                  @csrf
                  <button type="submit"
                          class="w-full bg-[#2D6A4F] hover:bg-[#1B4332] text-white py-2 px-4 rounded-xl transition">
                    Inscribirse
                  </button>
                </form>
              @endif
            </div>
          </div>

          <!-- Modal -->
          <div id="modal-{{ $evento->id }}" class="fixed inset-0 hidden justify-center items-center z-50">
            <div class="absolute inset-0 bg-white/20 backdrop-blur-sm" data-modal-close></div>
            <div class="relative bg-white rounded-xl max-w-md w-full p-6 shadow-xl overflow-y-auto max-h-[90vh]">
              <h4 class="text-lg font-bold mb-2 text-[#2D6A4F]">{{ $evento->titulo }}</h4>
              <p class="text-sm text-gray-700 mb-2"><strong>Descripción:</strong></p>
              <div class="editor-content text-sm text-gray-600">{!! $evento->descripcion !!}</div>
              <button class="cerrar-modal-btn absolute top-2 right-2 text-gray-500 hover:text-red-500 text-xl"
                      data-modal-close>&times;</button>
            </div>
          </div>
        @empty
          <p class="text-gray-500 italic">No hay eventos diarios disponibles.</p>
        @endforelse
      </div>
    </section>

    <!-- Eventos Semanales -->
    <section>
      <h2 class="text-2xl font-semibold mb-4 text-[#2D6A4F]">Eventos Semanales</h2>
      <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($eventosSemanales as $evento)
          @php
            $inicioSemana = Carbon::parse($evento->fecha)->startOfWeek();
            $finSemana = $inicioSemana->copy()->addDays(6);
            $color = $evento->categoria->color ?? '#CBD5E0';
            $nombreCategoria = $evento->categoria->nombre ?? 'Sin categoría';
          @endphp
          <div class="bg-white border-l-8 rounded-xl shadow-md hover:shadow-lg p-6 border border-gray-100 transition relative"
               style="border-left-color: {{ $color }};">
            <h3 class="text-lg font-semibold text-[#328E6E]">{{ $evento->titulo }}</h3>
            <p class="text-xs font-semibold text-gray-500 mt-1">Categoría: {{ $nombreCategoria }}</p>
            <p class="text-sm text-gray-700 mt-1">
              <strong>Semana:</strong> {{ $inicioSemana->format('Y-m-d') }} al {{ $finSemana->format('Y-m-d') }}
            </p>
            <p class="text-sm text-gray-700"><strong>Lugar:</strong> {{ $evento->lugar }}</p>
            <button class="ver-mas-btn text-sm text-[#328E6E] hover:underline mt-2" data-id="{{ $evento->id }}">
              Ver más
            </button>
            <div class="mt-4">
              <a href="{{ route('usuario.evento.dias', $evento->id) }}"
                 class="block w-full text-center bg-[#328E6E] hover:bg-[#287256] text-white py-2 px-4 rounded-xl font-semibold transition shadow-sm">
                Ingresar al evento
              </a>
            </div>
          </div>

          <!-- Modal -->
          <div id="modal-{{ $evento->id }}" class="fixed inset-0 hidden justify-center items-center z-50">
            <div class="absolute inset-0 bg-white/20 backdrop-blur-sm" data-modal-close></div>
            <div class="relative bg-white rounded-xl max-w-md w-full p-6 shadow-xl overflow-y-auto max-h-[90vh]">
              <h4 class="text-lg font-bold mb-2 text-[#2D6A4F]">{{ $evento->titulo }}</h4>
              <p class="text-sm text-gray-700 mb-2"><strong>Descripción:</strong></p>
              <div class="editor-content text-sm text-gray-600">{!! $evento->descripcion !!}</div>
              <button class="cerrar-modal-btn absolute top-2 right-2 text-gray-500 hover:text-red-500 text-xl"
                      data-modal-close>&times;</button>
            </div>
          </div>
        @empty
          <p class="text-gray-500 italic">No hay eventos semanales disponibles.</p>
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
