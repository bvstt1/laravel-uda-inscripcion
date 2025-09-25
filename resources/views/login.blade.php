<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login de Usuarios</title>
  @vite('resources/css/app.css')
  @vite('resources/js/app.js')
</head>
<body class="bg-gray-50 flex items-center justify-center min-h-screen">
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


    <form action="{{ route('login') }}" method="POST" onsubmit="return validarFormulario();" class="space-y-4">
      @csrf

      <input type="text" id="rut" name="rut" placeholder="RUT" maxlength="10" required
        oninput="validarRutSoloNumeros(this)"
        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#328E6E]">
      <span id="mensaje-rut" class="text-gray-400 text-sm">Escribe tu rut sin puntos ni guión</span>

      <!-- Mensaje de error dinámico (JS) -->
      <p class="text-red-500 text-sm mt-1 transition-opacity duration-300 hidden" id="alerta-rut"></p>

      <div class="relative">
        <input type="password" id="contrasena" name="contrasena" placeholder="Contraseña" required
              oninput="validarContrasena(this)"
              class="w-full border border-gray-300 rounded-lg px-4 py-2 pr-10 focus:outline-none focus:ring-2 focus:ring-[#328E6E]">

        <!-- Icono ojo -->
        <button type="button" id="toggleContrasena" 
                class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-700">
          <!-- SVG ojo abierto -->
          <svg id="iconoOjo" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                  d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                  d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
          </svg>
        </button>
      </div>

      <span id="mensaje-contrasena" class="text-red-500 text-sm hidden"></span>

      <div class="text-right">
        <a href="{{ route('password.request') }}" class="text-sm text-[#328E6E] hover:underline">
          ¿Has olvidado tu contraseña?
        </a>
      </div>

      <button type="submit" name="login"
        class="w-full bg-[#328E6E] text-white py-2 rounded-lg hover:bg-[#287256] transition shadow-lg transform hover:scale-[1.02]">
        Iniciar Sesión
      </button>
    </form>


    <div class="mt-4 text-center">
      <a href="{{ route('userSelection') }}" class="text-sm text-gray-600 hover:underline">Crear una nueva cuenta</a>
    </div>
    <a href="{{ route('welcome') }}" class="mt-6 flex justify-center">
      <img src="{{ asset('img/logo-uda.png') }}" alt="Logo Universidad de Atacama" class="h-12">
    </a>
  </div>
  
  <script src="{{ asset('js/validacionLoginRegistro.js') }}"></script>
</body>
</html>
