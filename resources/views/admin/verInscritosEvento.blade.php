<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Inscritos - {{ $evento->titulo }}</title>
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
    <div class="max-w-6xl mx-auto">
      <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-[#328E6E]">Inscritos en: "{{ $evento->titulo }}"</h1>
        @php
            // Si es evento diario, tiene id_evento_padre
            $rutaVolver = $evento->id_evento_padre
                ? route('admin.inscritos.semana', ['id' => $evento->id_evento_padre])
                : route('admin.inscripciones'); // si es evento independiente
        @endphp

        <a href="{{ $rutaVolver }}" class="inline-block px-3 py-1 bg-emerald-100 text-emerald-800 rounded-md hover:bg-emerald-200 transition">
            &larr; Volver
        </a>
      </div>

      @if($inscripcionesEstudiantes->isEmpty() && $inscripcionesExternos->isEmpty())
        <p class="text-gray-500 italic">No hay inscritos para este evento.</p>
      @else
        <div class="mb-4">
          <a href="{{ route('admin.exportar.excel', $evento->id) }}"
            class="bg-green-600 hover:bg-green-700 text-white py-2 px-4 rounded-lg transition">
            Exportar a Excel
          </a>
        </div>
      @endif

      @if(!$inscripcionesEstudiantes->isEmpty())
        <h2 class="text-lg font-bold text-[#328E6E] mt-6 mb-2">Estudiantes</h2>
        <div class="overflow-x-auto bg-white rounded-lg shadow p-4 mb-6">
          <table class="min-w-full table-auto text-sm">
            <thead class="bg-gray-200 text-left">
                <tr>
                    <th class="px-4 py-2">RUT</th>
                    <th class="px-4 py-2">Nombre</th>
                    <th class="px-4 py-2">Apellido</th>
                    <th class="px-4 py-2">Correo</th>
                    <th class="px-4 py-2">Carrera</th>
                    <th class="px-4 py-2">Fecha Inscripción</th>
                    <th class="px-4 py-2">Asistencia</th>
                    <th class="px-4 py-2">Estado</th>
                </tr>
            </thead>
            <tbody>
                @foreach($inscripcionesEstudiantes as $i)
                    @php
                        $est = \App\Models\Estudiante::where('rut', $i->rut_usuario)->first();
                    @endphp
                    <tr class="border-t">
                        <td class="px-4 py-2">{{ $i->rut_usuario }}</td>
                        <td class="px-4 py-2">{{ $est?->nombre }}</td>
                        <td class="px-4 py-2">{{ $est?->apellido }}</td>
                        <td class="px-4 py-2">{{ $est?->correo }}</td>
                        <td class="px-4 py-2">{{ $est?->carrera }}</td>
                        <td class="px-4 py-2">{{ \Carbon\Carbon::parse($i->fecha_inscripcion)->format('Y-m-d H:i') }}</td>
                        <td class="px-4 py-2">{{ $i->asistio_at ? 'Asistió' : '' }}</td>
                        <td class="px-4 py-2">
                            @if ($i->estado === 'desinscrito')
                                <span class="text-red-600 font-semibold">Desinscrito</span>
                            @else
                                <span class="text-green-600 font-medium">Inscrito</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
          </table>
        </div>
      @endif

      @if(!$inscripcionesExternos->isEmpty())
        <h2 class="text-lg font-bold text-[#328E6E] mb-2">Externos</h2>
        <div class="overflow-x-auto bg-white rounded-lg shadow p-4">
          <table class="min-w-full table-auto text-sm">
            <thead class="bg-gray-200 text-left">
              <tr>
                <th class="px-4 py-2">RUT</th>
                <th class="px-4 py-2">Nombre</th>
                <th class="px-4 py-2">Apellido</th>
                <th class="px-4 py-2">Correo</th>
                <th class="px-4 py-2">Institución</th>
                <th class="px-4 py-2">Cargo</th>
                <th class="px-4 py-2">Fecha Inscripción</th>
                <th class="px-4 py-2">Asistencia</th>
                <th class="px-4 py-2">Estado</th>
              </tr>
            </thead>
            <tbody>
              @foreach($inscripcionesExternos as $i)
                @php
                  $ext = \App\Models\Externo::where('rut', $i->rut_usuario)->first();
                @endphp
                <tr class="border-t">
                  <td class="px-4 py-2">{{ $i->rut_usuario }}</td>
                  <td class="px-4 py-2">{{ $ext?->nombre }}</td>
                  <td class="px-4 py-2">{{ $ext?->apellido }}</td>
                  <td class="px-4 py-2">{{ $ext?->correo }}</td>
                  <td class="px-4 py-2">{{ $ext?->institucion }}</td>
                  <td class="px-4 py-2">{{ $ext?->cargo }}</td>
                  <td class="px-4 py-2">{{ \Carbon\Carbon::parse($i->fecha_inscripcion)->format('Y-m-d H:i') }}</td>
                  <td class="px-4 py-2">{{ $i->asistio_at ? 'Asistió' : '' }}</td>
                  <td class="px-4 py-2">
                    @if ($i->estado === 'desinscrito')
                      <span class="text-red-600 font-semibold">Desinscrito</span>
                    @else
                      <span class="text-green-600 font-medium">Inscrito</span>
                    @endif
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
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
