<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Días del Evento Semanal</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans min-h-screen py-10 px-6">

  <div class="max-w-6xl mx-auto">
    <!-- Encabezado -->
    <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
      <h1 class="text-3xl font-bold text-[#328E6E]">Días del Evento: "{{ $eventoSemanal->titulo }}"</h1>
      <a href="{{ route('admin.inscripciones') }}" class="text-sm text-blue-600 hover:underline">&larr; Volver</a>
    </div>

    @if($eventosDiarios->isEmpty())
      <p class="text-gray-500 italic">No hay eventos diarios asociados a esta semana.</p>
    @else
      <div class="grid sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @foreach($eventosDiarios as $evento)
          <a href="{{ route('admin.inscritos.evento', $evento->id) }}"
             class="bg-white rounded-xl shadow-md hover:shadow-lg p-5 border border-gray-100 transition transform hover:-translate-y-1">
            <h3 class="text-lg font-semibold text-[#328E6E]">{{ $evento->titulo }}</h3>
            <p class="text-sm text-gray-700 mt-1"><strong>Fecha:</strong> {{ \Carbon\Carbon::parse($evento->fecha)->translatedFormat('l d-m-Y') }}</p>
          </a>
        @endforeach
      </div>
    @endif
  </div>

</body>
</html>
