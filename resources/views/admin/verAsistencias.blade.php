<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Registro de Asistencias</title>
  @vite('resources/css/app.css')
  @vite('resources/js/app.js')
</head>
<body class="bg-gray-100 py-10 px-6 font-sans">
  <div class="max-w-5xl mx-auto bg-white p-6 rounded-xl shadow-lg">
      <div class="flex justify-end space-x-3 mb-6">
        <a href="{{ route('panel') }}"
        class="inline-block px-3 py-1 bg-emerald-100 text-emerald-800 rounded-md hover:bg-emerald-200 transition">
            ← Volver al Panel
        </a>
        <a href="/logout" class="inline-block px-3 py-1 bg-red-100 text-red-700 rounded-md hover:bg-red-200 transition">Cerrar sesión</a>
      </div>
    <h1 class="text-3xl font-bold text-[#328E6E] mb-6 text-center">Registro de Asistencias</h1>

    <form method="GET" action="{{ route('admin.asistencias.buscar') }}" class="flex flex-col md:flex-row gap-4 mb-8">
      <select name="filtro" class="border rounded p-2 w-full md:w-1/3" required>
        <option value="">-- Filtrar por --</option>
        <option value="dia" {{ request('filtro') == 'dia' ? 'selected' : '' }}>Día</option>
        <option value="semana" {{ request('filtro') == 'semana' ? 'selected' : '' }}>Semana</option>
        <option value="mes" {{ request('filtro') == 'mes' ? 'selected' : '' }}>Mes</option>
      </select>

      <input type="date" name="fecha" value="{{ request('fecha') }}" class="border rounded p-2 w-full md:w-1/3" required>

      <button type="submit" class="bg-[#328E6E] text-white px-4 py-2 rounded hover:bg-[#287256] w-full md:w-1/3">Buscar</button>
    </form>

    @if(isset($asistencias))
      <div class="overflow-x-auto">
        <table class="min-w-full text-sm text-left border">
          <thead class="bg-gray-200">
            <tr>
              <th class="px-4 py-2 border">RUT</th>
              <th class="px-4 py-2 border">Correo</th>
              <th class="px-4 py-2 border">Carrera</th>
              <th class="px-4 py-2 border">Fecha Registro</th>
            </tr>
          </thead>
          <tbody>
            @forelse ($asistencias as $asistencia)
              <tr>
                <td class="px-4 py-2 border">{{ $asistencia['rut'] }}</td>
                <td class="px-4 py-2 border">{{ $asistencia['correo'] }}</td>
                <td class="px-4 py-2 border">{{ $asistencia['carrera'] }}</td>
                <td class="px-4 py-2 border">{{ $asistencia['fecha'] }}</td>
              </tr>
            @empty
              <tr>
                <td colspan="4" class="text-center px-4 py-4">No se encontraron asistencias para ese filtro.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      <div class="mt-6 text-right">
        <a href="{{ route('admin.asistencias.exportar', ['filtro' => request('filtro'), 'fecha' => request('fecha')]) }}"
           class="inline-block bg-[#328E6E] text-white px-4 py-2 rounded hover:bg-blue-[#287256]">Exportar Excel</a>
      </div>
    @endif
  </div>
</body>
</html>
