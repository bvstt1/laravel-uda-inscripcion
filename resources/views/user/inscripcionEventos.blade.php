@php use Carbon\Carbon; @endphp

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Eventos Disponibles</title>
  <link rel="stylesheet" href="{{ asset('css/ckeditor-content.css') }}">
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap">
  <style> body { font-family: 'Inter', sans-serif; } </style>´
</head>
<body class="bg-gray-50 min-h-screen text-gray-800">
  <div class="max-w-7xl mx-auto py-10 px-6">
    <div class="flex flex-col md:flex-row justify-between items-center mb-10 gap-4">
      <h1 class="text-4xl font-bold text-[#1B4332]">🗓️ Eventos Disponibles</h1>
      <div class="flex gap-4 text-sm">
        <a href="{{ route('cuenta.formulario') }}" class="inline-block px-3 py-1 bg-emerald-100 text-emerald-800 rounded-md hover:bg-emerald-200 transition">👤 Mi cuenta</a>
        <a href="/logout" class="inline-block px-3 py-1 bg-red-100 text-red-700 rounded-md hover:bg-red-200 transition">🔐 Cerrar Sesión</a>
      </div>
    </div>

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

    <form method="GET" class="mb-8 flex items-center gap-2">
      <label class="text-sm font-medium text-gray-700">Filtrar por categoría:</label>
      <select name="categoria_id" onchange="this.form.submit()" class="p-2 border rounded-lg">
        <option value="">Todas</option>
        @foreach($categorias as $cat)
          <option value="{{ $cat->id }}" {{ request('categoria_id') == $cat->id ? 'selected' : '' }}>
            {{ $cat->nombre }}
          </option>
        @endforeach
      </select>
    </form>

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
              @if($evento->hora_termino) - {{ Carbon::parse($evento->hora_termino)->format('H:i') }} @endif</p>
            <p class="text-sm text-gray-700"><strong>Lugar:</strong> {{ $evento->lugar }}</p>
            <button class="ver-mas-btn text-sm text-[#328E6E] hover:underline mt-2" data-id="{{ $evento->id }}">Ver más</button>
            <div class="mt-4">
              @if ($estado === 'inscrito')
                <form method="POST" action="{{ route('desinscribirse', $evento->id) }}" onsubmit="return confirm('¿Estás seguro que deseas desinscribirte?, una ves desinscrito no podras volver a inscribirte a este evento.')">
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

          <div id="modal-{{ $evento->id }}" class="fixed inset-0 bg-black bg-opacity-50 justify-center items-center hidden z-50">
            <div class="bg-white p-6 rounded-xl max-w-md w-full relative">
              <h4 class="text-lg font-bold mb-2 text-[#2D6A4F]">{{ $evento->titulo }}</h4>
              <p class="text-sm text-gray-700 mb-2"><strong>Descripción:</strong></p>
              <div class="editor-content text-sm text-gray-600">{!! $evento->descripcion !!}</div>
              <button class="cerrar-modal-btn absolute top-2 right-2 text-gray-500 hover:text-red-500 text-xl" data-id="{{ $evento->id }}">&times;</button>
            </div>
          </div>


        @empty
          <p class="text-gray-500 italic">No hay eventos diarios disponibles.</p>
        @endforelse
      </div>
    </section>

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
              <a href="{{ route('usuario.evento.dias', $evento->id) }}" 
                class="mt-4 block w-full text-center bg-[#328E6E] hover:bg-[#287256] text-white py-2 px-4 rounded-xl font-semibold transition shadow-sm">
                📅 Ingresar al evento
              </a>
            </div>
          </div>

          <div id="modal-{{ $evento->id }}" class="fixed inset-0 bg-black bg-opacity-50 justify-center items-center hidden z-50">
            <div class="bg-white p-6 rounded-xl max-w-md w-full relative">
              <h4 class="text-lg font-bold mb-2 text-[#2D6A4F]">{{ $evento->titulo }}</h4>
              <p class="text-sm text-gray-700 mb-2"><strong>Descripción:</strong></p>
              <div class="editor-content text-sm text-gray-600">{!! $evento->descripcion !!}</div>
              <button class="cerrar-modal-btn absolute top-2 right-2 text-gray-500 hover:text-red-500 text-xl" data-id="{{ $evento->id }}">&times;</button>
            </div>
          </div>

        @empty
          <p class="text-gray-500 italic">No hay eventos semanales disponibles.</p>
        @endforelse
      </div>
    </section>
  </div>

  <script>
    document.querySelectorAll('.ver-mas-btn').forEach(btn => {
      btn.addEventListener('click', () => {
        const modal = document.getElementById(`modal-${btn.dataset.id}`);
        modal?.classList.remove('hidden');
        modal?.classList.add('flex');
      });
    });

    document.querySelectorAll('.cerrar-modal-btn').forEach(btn => {
      btn.addEventListener('click', () => {
        const modal = document.getElementById(`modal-${btn.dataset.id}`);
        modal?.classList.remove('flex');
        modal?.classList.add('hidden');
      });
    });
  </script>
</body>
</html>
