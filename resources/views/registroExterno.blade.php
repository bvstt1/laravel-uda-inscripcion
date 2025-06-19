<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Registro Usuario Externo</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
  <div class="bg-white rounded-2xl shadow-lg p-8 w-full max-w-sm">
    <h2 class="text-2xl font-bold text-center text-[#328E6E] mb-1">Crear cuenta</h2>
    <p class="text-center text-md text-gray-700 mb-2">
      Usuario <span class="text-[#328E6E] font-semibold">Externo</span>
    </p>
    <div class="text-center">
      <span id="mensaje-rut" class="text-gray-400 text-sm">Escribe tu rut sin puntos ni guión</span>  
    </div>
    <form action="/registroExterno" method="POST" onsubmit="return validarFormulario();" class="space-y-4">
      @csrf 
    <input type="text" id="rut" name="rut" maxlength="10" placeholder="RUT" required
      oninput="validarRutSoloNumeros(this)"
      class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#328E6E]">
      @error('rut')
        <p class="text-red-500 text-sm mt-1 transition-opacity duration-300" id="alerta-rut">{{ $message }}</p>
      @enderror

      <input type="email" name="correo" placeholder="correo@ejemplo.com" required
        class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#328E6E]">
      @error('correo')
        <p class="text-red-500 text-sm mt-1 transition-opacity duration-300" id="alerta-correo">{{ $message }}</p>
      @enderror

      <input type="text" name="institucion" placeholder="Institución" required
        class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#328E6E]">

      <input type="text" name="cargo" placeholder="Cargo" required
        class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#328E6E]">

      <input type="password" name="contrasena" id="contrasena" placeholder="Contraseña"
        oninput="validarContrasena(this)"
        required
        class="w-full border border-gray-200 bg-gray-100 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#328E6E]">
        <span id="mensaje-contrasena" class="text-red-500 text-sm hidden"></span>

      <input type="password" name="contrasena_confirmation" id="contrasena_confirmation" placeholder="Confirmar Contraseña" required 
      oninput="validarConfirmacionContrasena()" 
      class="w-full border border-gray-200 bg-gray-100 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#328E6E]">

      <!-- Mensaje de JS (en vivo) -->
      <span id="mensaje-confirmacion-js" class="text-red-500 text-sm hidden"></span>

      <!-- Mensaje de Laravel (backend) -->
      @error('contrasena_confirmation')
        <span class="text-red-500 text-sm block">{{ $message }}</span>
      @enderror
      
      <button type="submit" name="registro-ext"
        class="w-full text-white py-2 rounded-lg bg-[#328E6E] transition hover:bg-[#287256] shadow-lg">
        Registrar
      </button>
    </form>

    <div class="mt-4 text-center">
      <a href="./login" class="text-sm text-gray-600 hover:underline">Ya tienes cuenta? Inicia Sesión aquí</a>
    </div>

    <div class="mt-6 flex justify-center">
      <img src="../img/logo-uda.png" alt="Logo Universidad de Atacama" class="h-12">
    </div>
  </div>


<script src="{{ secure_asset('js/validacionLoginRegistro.js') }}"></script>
</body>
</html>
