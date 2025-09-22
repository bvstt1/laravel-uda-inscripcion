<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Crear cuenta</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
  <div class="bg-white rounded-2xl shadow-lg p-8 w-full max-w-sm">
    <h2 class="text-2xl font-bold text-center text-[#328E6E] mb-2">Crear cuenta</h2>
    <p class="text-center text-sm text-gray-600 mb-6">
      Selecciona tu tipo de <span class="text-[#328E6E] font-semibold">usuario</span>
    </p>

    <div class="space-y-3">
      <!-- Opción Estudiante -->
      <a href="/registroEstudiante"
        class="block w-full bg-[#328E6E] text-white font-medium text-center py-3 rounded-xl shadow hover:bg-[#287256] transition transform hover:scale-[1.02]">
        Estudiante UDA
      </a>

      <!-- Opción Externo -->
      <a href="/registroExterno"
        class="block w-full bg-gray-200 text-gray-800 font-medium text-center py-3 rounded-xl hover:bg-gray-300 transition transform hover:scale-[1.02]">
        Usuario Externo
      </a>
    </div>

    <div class="mt-4 text-center">
      <a href="./login" class="text-sm text-gray-600 hover:underline">
        ¿Ya tienes cuenta? <span class="text-[#328E6E] font-semibold">Inicia Sesión aquí</span>
      </a>
    </div>

    <div class="mt-6 flex justify-center">
      <img src="{{ asset('img/logo-uda.png') }}" alt="Logo Universidad de Atacama" class="h-12">
    </div>
  </div>
</body>
</html>
