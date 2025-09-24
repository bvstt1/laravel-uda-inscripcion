<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Panel Administrador</title>
  @vite('resources/css/app.css')
  @vite('resources/js/app.js')
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
  <style>
    body { font-family: 'Inter', sans-serif; }
  </style>
</head>
<body class="bg-gradient-to-br from-gray-100 to-gray-200 min-h-screen flex items-center justify-center text-gray-800">
  <div class="bg-white rounded-2xl p-8 w-full max-w-md text-center border border-gray-200">
    
    <!-- Logo -->
    <div class="flex justify-center mb-6">
      <img src="{{ asset('img/logo-uda.png') }}" alt="Logo Universidad de Atacama" class="h-14">
    </div>

    <!-- Título -->
    <h2 class="text-3xl font-bold text-[#287256] mb-6">Panel del Administrador</h2>

    <!-- Opciones -->
    <div class="flex flex-col space-y-4 mb-8 text-sm font-medium">
      <a href="{{ route('eventos.create') }}" class="block w-full bg-[#328E6E] hover:bg-[#287256] text-white py-3 rounded-xl shadow-md transition">
        Crear Evento
      </a>
      <a href="{{ route('eventos.index') }}" class="block w-full bg-[#328E6E] hover:bg-[#287256] text-white py-3 rounded-xl shadow-md transition">
        Ver / Editar / Eliminar Evento
      </a>
      <a href="{{ route('admin.inscripciones') }}" class="block w-full bg-[#328E6E] hover:bg-[#287256] text-white py-3 rounded-xl shadow-md transition">
        Ver Inscritos y Asistencias a los Eventos
      </a>
      <a href="{{ route('admin.asistencias.filtro') }}" class="block w-full bg-[#328E6E] hover:bg-[#287256] text-white py-3 rounded-xl shadow-md transition">
        Ver Asistencias del HUB
      </a>
      <a href="{{ route('totem.selector') }}" class="block w-full bg-[#328E6E] hover:bg-[#287256] text-white py-3 rounded-xl shadow-md transition">
        Abrir Totém de Asistencia a Eventos
      </a>
      <a href="{{ route('totem.libre') }}" class="block w-full bg-[#328E6E] hover:bg-[#287256] text-white py-3 rounded-xl shadow-md transition">
        Abrir Totém de Asistencia Libre
      </a>
    </div>

    <!-- Logout -->
    <a href="/logout" class="inline-block bg-red-600 hover:bg-red-700 text-white text-sm px-5 py-2 rounded-xl transition hover:underline">
      Cerrar sesión
    </a>
  </div>
</body>
</html>
