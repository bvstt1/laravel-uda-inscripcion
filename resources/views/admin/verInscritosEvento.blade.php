<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Inscritos - {{ $evento->titulo }}</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans min-h-screen py-10 px-6">

  <div class="max-w-6xl mx-auto">
    <div class="flex justify-between items-center mb-6">
      <h1 class="text-2xl font-bold text-[#328E6E]">Inscritos en: "{{ $evento->titulo }}"</h1>
      <a href="{{ route('admin.inscripciones') }}" class="inline-block px-3 py-1 bg-emerald-100 text-emerald-800 rounded-md hover:bg-emerald-200 transition">&larr; Volver</a>
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
</body>
</html>
