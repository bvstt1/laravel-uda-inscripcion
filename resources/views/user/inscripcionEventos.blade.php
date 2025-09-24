@php use Carbon\Carbon; @endphp

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
      <a class="text-2xl md:text-3xl font-bold">Eventos Disponibles</h1>
      
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
          if (msg) { msg.style.opacity = '0'; setTimeout(() => msg.remove(), 500); }
        }, 3000);
      </script>
    @endif

    <!-- Filtro de categorías -->
    <form method="GET" class="mb-8 flex items-center gap-2">
      <label class="text-sm font-medium text-gray-700">Filtrar por categoría:</label>
      <select name="categoria_id" onchange="this.form.submit()" class="p-2 border rounded-lg">
        <option value="">Todas</option>
        @foreach($categorias as $cat)
          <option value="{{ $cat->id }}" {{ request('categoria_id') == $cat->id ? 'selected' : '' }}>{{ $cat->nombre }}</option>
        @endforeach
      </select>
    </form>

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

          <div class="bg-white border-l-8 rounded-xl shadow-md hover:shadow-lg p-6 border border-gray-100 transition relative" style="border-left-color: {{ $color }};">
            <h3 class="text-lg font-semibold text-[#328E6E]">{{ $evento->titulo }}</h3>
            <p class="text-xs font-semibold text-gray-500 mt-1">Categoría: {{ $nombreCategoria }}</p>
            <p class="text-sm text-gray-700 mt-1"><strong>Fecha:</strong> {{ $evento->fecha }}</p>
            <p class="text-sm text-gray-700"><strong>Horario:</strong> {{ Carbon::parse($evento->hora)->format('H:i') }}
              @if($evento->hora_termino) - {{ Carbon::parse($evento->hora_termino)->format('H:i') }} @endif
            </p>
            <p class="text-sm text-gray-700"><strong>Lugar:</strong> {{ $evento->lugar }}</p>

            <button class="ver-mas-btn text-sm text-[#328E6E] hover:underline mt-2" data-id="{{ $evento->id }}">Ver más</button>

            <div class="mt-4">
              @if ($estado === 'inscrito')
                <form method="POST" action="{{ route('desinscribirse', $evento->id) }}" onsubmit="return confirm('¿Estás seguro que deseas desinscribirte? Una vez desinscrito no podrás volver a inscribirte.')">
                  @csrf @method('DELETE')
                  <button type="submit" class="w-full bg-red-500 hover:bg-red-600 text-white py-2 px-4 rounded-xl transition">Desinscribirse</button>
                </form>
              @elseif ($estado === 'desinscrito')
                <div class="text-sm text-gray-500 italic mt-2">Ya te desinscribiste.</div>
              @else
                <form method="POST" action="{{ route('inscribirse', $evento->id) }}" onsubmit="return confirm('¿Deseas inscribirte?')">
                  @csrf
                  <button type="submit" class="w-full bg-[#2D6A4F] hover:bg-[#1B4332] text-white py-2 px-4 rounded-xl transition">Inscribirse</button>
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
              <button class="cerrar-modal-btn absolute top-2 right-2 text-gray-500 hover:text-red-500 text-xl" data-modal-close>&times;</button>
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

          <div class="bg-white border-l-8 rounded-xl shadow-md hover:shadow-lg p-6 border border-gray-100 transition relative" style="border-left-color: {{ $color }};">
            <h3 class="text-lg font-semibold text-[#328E6E]">{{ $evento->titulo }}</h3>
            <p class="text-xs font-semibold text-gray-500 mt-1">Categoría: {{ $nombreCategoria }}</p>
            <p class="text-sm text-gray-700 mt-1"><strong>Semana:</strong> {{ $inicioSemana->format('Y-m-d') }} al {{ $finSemana->format('Y-m-d') }}</p>
            <p class="text-sm text-gray-700"><strong>Lugar:</strong> {{ $evento->lugar }}</p>

            <button class="ver-mas-btn text-sm text-[#328E6E] hover:underline mt-2" data-id="{{ $evento->id }}">Ver más</button>

            <div class="mt-4">
              <a href="{{ route('usuario.evento.dias', $evento->id) }}" class="block w-full text-center bg-[#328E6E] hover:bg-[#287256] text-white py-2 px-4 rounded-xl font-semibold transition shadow-sm">
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
              <button class="cerrar-modal-btn absolute top-2 right-2 text-gray-500 hover:text-red-500 text-xl" data-modal-close>&times;</button>
            </div>
          </div>

        @empty
          <p class="text-gray-500 italic">No hay eventos semanales disponibles.</p>
        @endforelse
      </div>
    </section>

  </main>

  <!-- Footer -->
<!-- Footer -->
<footer class="bg-gray-900 text-gray-300 py-10 mt-16">
  <div class="max-w-7xl mx-auto px-6 flex flex-col md:flex-row justify-between items-center gap-6">

    <!-- Redes sociales -->
    <div class="flex items-center space-x-4">
      <a href="https://www.facebook.com/UDAinstitucional" target="_blank" class="hover:text-white transition">
        <svg class="w-6 h-6 fill-current" viewBox="0 0 24 24">
          <path d="M22 12a10 10 0 10-11.5 9.95v-7.05H8v-3h2.5V9.5a3.5 3.5 0 013.5-3.5h2v3h-2a.5.5 0 00-.5.5V12H17l-.5 3h-2v7.05A10 10 0 0022 12z"/>
        </svg>
      </a>
      <a href="https://x.com/UAtacama" target="_blank" class="hover:text-white transition">
        <svg class="w-6 h-6 fill-current" viewBox="0 0 24 24">
          <path d="M22.46 6c-.77.35-1.5.58-2.33.69a4.1 4.1 0 001.8-2.27 8.3 8.3 0 01-2.6.99 4.14 4.14 0 00-7.06 3.77 11.74 11.74 0 01-8.53-4.32 4.13 4.13 0 001.28 5.53 4.1 4.1 0 01-1.87-.52v.05a4.14 4.14 0 003.32 4.05 4.1 4.1 0 01-1.86.07 4.14 4.14 0 003.87 2.87A8.3 8.3 0 012 19.54a11.73 11.73 0 006.29 1.84c7.55 0 11.68-6.25 11.68-11.66 0-.18 0-.35-.01-.53A8.36 8.36 0 0024 5.32a8.2 8.2 0 01-2.36.65 4.1 4.1 0 001.8-2.27"/>
        </svg>
      </a>
      <a href="https://www.instagram.com/u_atacama/" target="_blank" class="hover:text-white transition">
        <svg class="w-6 h-6 fill-current" viewBox="0 0 24 24">
          <path d="M12 2.2c3.2 0 3.584.012 4.85.07 1.17.054 1.97.24 2.43.403a4.92 4.92 0 011.75 1.075 4.92 4.92 0 011.075 1.75c.163.46.35 1.26.403 2.43.058 1.266.07 1.65.07 4.85s-.012 3.584-.07 4.85c-.054 1.17-.24 1.97-.403 2.43a4.92 4.92 0 01-1.075 1.75 4.92 4.92 0 01-1.75 1.075c-.46.163-1.26.35-2.43.403-1.266.058-1.65.07-4.85.07s-3.584-.012-4.85-.07c-1.17-.054-1.97-.24-2.43-.403a4.92 4.92 0 01-1.75-1.075 4.92 4.92 0 01-1.075-1.75c-.163-.46-.35-1.26-.403-2.43C2.212 15.584 2.2 15.2 2.2 12s.012-3.584.07-4.85c.054-1.17.24-1.97.403-2.43a4.92 4.92 0 011.075-1.75 4.92 4.92 0 011.75-1.075c.46-.163 1.26-.35 2.43-.403C8.416 2.212 8.8 2.2 12 2.2zm0 1.8c-3.163 0-3.537.012-4.787.07-1.048.05-1.617.22-1.993.37a3.12 3.12 0 00-1.133.738 3.12 3.12 0 00-.738 1.133c-.15.376-.32.945-.37 1.993-.058 1.25-.07 1.624-.07 4.787s.012 3.537.07 4.787c.05 1.048.22 1.617.37 1.993.17.447.403.835.738 1.133.298.335.686.568 1.133.738.376.15.945.32 1.993.37 1.25.058 1.624.07 4.787.07s3.537-.012 4.787-.07c1.048-.05 1.617-.22 1.993-.37a3.12 3.12 0 001.133-.738 3.12 3.12 0 00.738-1.133c.15-.376.32-.945.37-1.993.058-1.25.07-1.624.07-4.787s-.012-3.537-.07-4.787c-.05-1.048-.22-1.617-.37-1.993a3.12 3.12 0 00-.738-1.133 3.12 3.12 0 00-1.133-.738c-.376-.15-.945-.32-1.993-.37-1.25-.058-1.624-.07-4.787-.07zM12 5.8a6.2 6.2 0 100 12.4 6.2 6.2 0 000-12.4zm0 1.8a4.4 4.4 0 110 8.8 4.4 4.4 0 010-8.8zm6.4-.85a1.45 1.45 0 11-2.9 0 1.45 1.45 0 012.9 0z"/>
        </svg>
      </a>
    </div>

    <!-- Crédito -->
    <div class="text-sm text-gray-400 text-center md:text-right">
      &copy; {{ date('Y') }} Universidad de Atacama.
    </div>

  </div>
</footer>


  <!-- Scripts -->
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      // Abrir modal
      document.querySelectorAll('.ver-mas-btn').forEach(btn => {
        btn.addEventListener('click', () => {
          const modal = document.getElementById(`modal-${btn.dataset.id}`);
          if(modal){
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.style.overflow = 'hidden';
          }
        });
      });

      // Cerrar modal
      document.querySelectorAll('.cerrar-modal-btn').forEach(btn => {
        btn.addEventListener('click', () => {
          const modal = btn.closest('.fixed');
          if(modal){
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.body.style.overflow = 'auto';
          }
        });
      });

      // Cerrar modal al hacer click fuera del contenido
      document.querySelectorAll('.fixed').forEach(modal => {
        modal.addEventListener('click', (e) => {
          if(e.target === modal){
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.body.style.overflow = 'auto';
          }
        });
      });
    });
  </script>

</body>
</html>
