<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Panel Administrador</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
  <div class="bg-white rounded-2xl shadow-lg p-8 w-full max-w-md text-center">
    <div class="flex justify-center mb-6">
      <img src="{{ asset('img/logo-uda.png') }}" alt="Logo Universidad de Atacama" class="h-12">
    </div>

    <h2 class="text-2xl font-bold text-[#328E6E]green-700 mb-4">Administrador</h2>

    <div class="flex flex-col space-y-4 mb-6">
      <a href="{{ route('eventos.create') }}" class="block w-full bg-[#328E6E] hover:bg-[#287256] text-white py-2 rounded-lg shadow-md transition">Crear Evento</a>
      <a href="{{ route('eventos.index')}}" class="block w-full bg-[#328E6E] hover:bg-[#287256] text-white py-2 rounded-lg shadow-md transition">Ver / Editar / Eliminar Evento</a>
      <a href="{{ route('admin.inscripciones') }}" class="block w-full bg-[#328E6E] hover:bg-[#287256] text-white py-2 rounded-lg shadow-md transition">Ver Inscritos y Asistencias</a>
      <a href="{{ route('admin.asistencias.filtro') }}" class="block w-full bg-[#328E6E] hover:bg-[#287256] text-white py-2 rounded-lg shadow-md transition">Ver Registro de Asistencias a las instalaciones</a>
      <a href="{{ route('totem.selector')}}" class="block w-full bg-[#328E6E] hover:bg-[#287256] text-white py-2 rounded-lg shadow-md transition">Abrir Totém de Asistencia a Eventos</a>      
      <a href="{{ route('totem.libre')}}" class="block w-full bg-[#328E6E] hover:bg-[#287256] text-white py-2 rounded-lg shadow-md transition">Abrir Totém de Asistencia Libre</a>        
    </div>

    <a href="/logout" class="bg-red-700 hover:bg-red-800 text-white px-4 py-2 rounded transition text-sm hover:underline">Cerrar sesión</a>
  </div>
</body>
</html>
