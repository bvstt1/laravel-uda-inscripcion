<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login de Usuarios</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
  <div class="bg-white rounded-2xl shadow-lg p-8 w-full max-w-sm">
    <h2 class="text-3xl font-bold text-center text-[#328E6E] mb-2">Bienvenido</h2>
    <p class="text-center text-sm text-gray-600 mb-6">
      Estás a un paso de vivir nuevas experiencias.<br>
      ¡Confirma tu participación!
    </p>

    
    @if (session('error'))
      <div id="mensaje-error-login"
          class="bg-red-100 text-red-700 text-sm rounded p-2 mb-4 text-center transition-opacity duration-300">
        {{ session('error') }}
      </div>
    @endif


    <form action="/login" method="POST" onsubmit="return validarFormulario();" class="space-y-4">
      @csrf

      <input type="text" id="rut" name="rut" placeholder="RUT" maxlength="10" required
        oninput="validarRutSoloNumeros(this)"
        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#328E6E]">
      <span id="mensaje-rut" class="text-gray-400 text-sm">Escribe tu rut sin puntos ni guión</span>

      <!-- Mensaje de error dinámico (JS) -->
      <p class="text-red-500 text-sm mt-1 transition-opacity duration-300 hidden" id="alerta-rut"></p>

      <input type="password" id="contrasena" name="contrasena" placeholder="Contraseña" required
        oninput="validarContrasena(this)"
        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#328E6E]">
      <span id="mensaje-contrasena" class="text-red-500 text-sm hidden"></span>

      <div class="text-right">
        <a href="#" class="text-sm text-[#328E6E] hover:underline">¿Has olvidado tu contraseña?</a>
      </div>

      <button type="submit" name="login"
        class="w-full bg-[#328E6E] text-white py-2 rounded-lg hover:bg-[#287256] transition shadow-lg">
        Iniciar Sesión
      </button>
    </form>


    <div class="mt-4 text-center">
      <a href="/userSelection" class="text-sm text-gray-600 hover:underline">Crear una nueva cuenta</a>
    </div>
    <div class="mt-6 flex justify-center">
      <img src="../img/logo-uda.png" alt="Logo Universidad de Atacama" class="h-12">
    </div>
  </div>
  
  <script src="{{ asset('js/vistaRut.js') }}"></script>
</body>
</html>
