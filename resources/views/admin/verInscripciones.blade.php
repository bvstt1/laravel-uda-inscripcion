<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Inscripciones por Evento</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans min-h-screen py-10 px-6">

  <div class="max-w-6xl mx-auto">
    <!-- Encabezado -->
    <div class="flex flex-col md:flex-row justify-between items-center mb-10 gap-4">
      <h1 class="text-4xl font-bold text-[#328E6E]">Gestión de Inscripciones</h1>
      <div class="flex gap-4 text-sm">
        <a href="{{ route('panel') }}" class="text-blue-600 hover:underline">&larr; Volver al panel</a>
        <a href="/logout" class="text-red-600 hover:underline">Cerrar sesión</a>
      </div>
    </div>

    <!-- Eventos Diarios Aislados -->
    <section class="mb-12">
      <h2 class="text-2xl font-semibold text-gray-800 mb-4">📅 Eventos Diarios Aislados</h2>

      @if($eventosDiariosAislados->isEmpty())
        <p class="text-gray-500 italic">No hay eventos diarios aislados disponibles.</p>
      @else
        <div class="grid sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
          @foreach($eventosDiariosAislados as $evento)
            <a href="{{ route('admin.inscritos.evento', $evento->id) }}"
               class="bg-white rounded-xl shadow-md hover:shadow-lg p-5 border border-gray-100 transition transform hover:-translate-y-1">
              <h3 class="text-lg font-semibold text-[#328E6E]">{{ $evento->titulo }}</h3>
              <p class="text-sm text-gray-700 mt-1"><strong>Fecha:</strong> {{ $evento->fecha }}</p>
            </a>
          @endforeach
        </div>
      @endif
    </section>

    <!-- Eventos Semanales -->
    <section>
      <h2 class="text-2xl font-semibold text-gray-800 mb-4">🗓️ Eventos Semanales</h2>

      @if($eventosSemanales->isEmpty())
        <p class="text-gray-500 italic">No hay eventos semanales disponibles.</p>
      @else
        <div class="grid sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
          @foreach($eventosSemanales as $evento)
            <a href="{{ route('admin.inscritos.semana', $evento->id) }}"
               class="bg-white rounded-xl shadow-md hover:shadow-lg p-5 border border-gray-100 transition transform hover:-translate-y-1">
              <h3 class="text-lg font-semibold text-[#328E6E]">{{ $evento->titulo }}</h3>
              <p class="text-sm text-gray-700 mt-1"><strong>Semana del:</strong> {{ $evento->fecha }}</p>
            </a>
          @endforeach
        </div>
      @endif
    </section>
  </div>

</body>
</html>
