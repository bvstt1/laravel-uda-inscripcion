<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Días del Evento Semanal</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  @vite('resources/css/app.css')
  @vite('resources/js/app.js')
  <script src="https://kit.fontawesome.com/your-kit-code.js" crossorigin="anonymous"></script>
</head>
<body class="bg-gray-100 font-sans min-h-screen flex flex-col">

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

  <!-- Contenido principal -->
  <main class="flex-grow py-10 px-6">
    <div class="max-w-7xl mx-auto">
      <!-- Encabezado de la página -->
      <div class="flex flex-col md:flex-row justify-between items-center mb-10 gap-4">
        <h1 class="text-4xl font-bold text-[#328E6E]">Días del Evento: "{{ $eventoSemanal->titulo }}"</h1>
        <a href="{{ route('admin.inscripciones')}}"
          class="inline-block px-3 py-1 bg-emerald-100 text-emerald-800 rounded-md hover:bg-emerald-200 transition">
          &larr; Volver
        </a>
      </div>

      @if($eventosDiarios->isEmpty())
        <p class="text-gray-500 italic">No hay eventos diarios asociados a esta semana.</p>
      @else
        <div class="grid sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          @foreach($eventosDiarios->sortBy('fecha') as $evento)
            @php
              $color = $evento->categoria->color ?? '#CBD5E0';
              $nombreCategoria = $evento->categoria->nombre ?? 'Sin categoría';
            @endphp
            <a href="{{ route('admin.inscritos.evento', $evento->id) }}"
               class="bg-white border-l-8 rounded-xl shadow-md hover:shadow-lg p-5 border border-gray-100 transition transform hover:-translate-y-1"
               style="border-left-color: {{ $color }};">
              <h3 class="text-lg font-semibold text-[#328E6E]">{{ $evento->titulo }}</h3>
              <p class="text-xs font-medium text-gray-500">Categoría: {{ $nombreCategoria }}</p>
              <p class="text-sm text-gray-700 mt-1">
                <strong>Fecha:</strong> {{ \Carbon\Carbon::parse($evento->fecha)->translatedFormat('l d-m-Y') }}
              </p>
            </a>
          @endforeach
        </div>
      @endif
    </div>
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
  <script src="https://kit.fontawesome.com/782e1f1389.js" crossorigin="anonymous"></script>
</body>
</html>
