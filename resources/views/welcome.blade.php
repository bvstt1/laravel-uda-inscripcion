<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Portal de Eventos</title>
  @vite('resources/css/app.css')
  @vite('resources/js/app.js')
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap">
  <style> body { font-family: 'Inter', sans-serif; } </style>
</head>
<body class="bg-gray-50 flex items-center justify-center min-h-screen px-4">
  <div class="max-w-4xl w-full bg-white rounded-2xl shadow-xl overflow-hidden">

    <!-- Encabezado -->
    <div class="bg-[#328E6E] p-8 text-center text-white">
      <h1 class="text-4xl font-bold mb-2">Portal de Eventos del HUB</h1>
      <p class="text-sm opacity-90">Universidad de Atacama</p>
    </div>

    <!-- Contenido -->
    <div class="p-8 grid md:grid-cols-2 gap-8">
      <div class="flex flex-col justify-center">
        <h2 class="text-2xl font-semibold text-[#2D6A4F] mb-3">Descubre y participa</h2>
        <p class="text-gray-600 mb-6">
          Accede a <span class="font-medium">charlas, talleres, seminarios y actividades</span> que el HUB de Innovación pone a disposición. 
          Una vez dentro, encontrarás la sección de <span class="font-semibold text-[#328E6E]">Eventos Disponibles</span> organizada en categorías diarias y semanales.
        </p>
        <div class="flex flex-col gap-3">
          <a href="{{ route('login') }}"
             class="inline-block text-center bg-[#328E6E] hover:bg-[#287256] text-white py-3 px-5 rounded-xl shadow-md font-semibold transition">
            Iniciar Sesión
          </a>
          <a href="{{ route('userSelection') }}"
             class="inline-block text-center bg-gray-100 hover:bg-gray-200 text-gray-800 py-3 px-5 rounded-xl font-semibold transition">
            Registrarse
          </a>
        </div>
      </div>

      <!-- Imagen / Logo -->
      <div class="flex justify-center items-center">
        <img src="{{ asset('img/logo-uda.png') }}" alt="Logo Universidad de Atacama" class="max-h-52">
      </div>
    </div>

    <!-- Footer -->
    <div class="bg-gray-100 text-center py-4 text-sm text-gray-500">
      © {{ date('Y') }} Universidad de Atacama
    </div>
  </div>
</body>
</html>
