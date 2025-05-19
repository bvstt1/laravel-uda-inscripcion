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
      Por favor selecciona tu tipo de <span class="text-[#328E6E] font-semibold">usuario</span>
    </p>

    <div class="space-y-4">
      <a href="./registerEstudiante" class="block">
        <div class="bg-gray-200 rounded-lg overflow-hidden shadow hover:shadow-md transition">
          <div class="h-32 bg-gray-300"></div>
          <div class="p-4 text-center font-medium text-gray-700 border-t">Estudiante UDA</div>
        </div>
      </a>

      <a href="./registerExterno" class="block">
        <div class="bg-gray-200 rounded-lg overflow-hidden shadow hover:shadow-md transition">
          <div class="h-32 bg-gray-300"></div>
          <div class="p-4 text-center font-medium text-gray-700 border-t">Externo</div>
        </div>
      </a>
    </div>

    <div class="mt-4 text-center">
      <a href="./" class="text-sm text-gray-600 hover:underline">Ya tienes cuenta? Inicia Sesión aquí</a>
    </div>

    <div class="mt-6 flex justify-center">
      <img src="../img/logo-uda.png" alt="Logo Universidad de Atacama" class="h-12">
    </div>
  </div>
</body>
</html>
