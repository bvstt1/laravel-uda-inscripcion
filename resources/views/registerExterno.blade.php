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
    <p class="text-center text-md text-gray-700 mb-6">
      Usuario <span class="text-[#328E6E] font-semibold">Externo</span>
    </p>

    <form action="/registerExterno" method="POST" onsubmit="return validarFormulario();" class="space-y-4">
      @csrf 
      <input type="text" id="rut" name="rut" maxlength="10" placeholder="RUT" oninput="soloNumeros(this)" required
        class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#328E6E]">
      
      <input type="email" name="correo" placeholder="correo@ejemplo.com" required
        class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#328E6E]">

      <input type="text" name="institucion" placeholder="Institución" required
        class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#328E6E]">

      <input type="text" name="cargo" placeholder="Cargo" required
        class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#328E6E]">

      <input type="password" name="contrasena" placeholder="Contraseña" required
        class="w-full border border-gray-200 bg-gray-100 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#328E6E]">
      
      <input type="password" name="confirmar_contrasena" placeholder="Confirmar Contraseña" required
        class="w-full border border-gray-200 bg-gray-100 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#328E6E]">

      <button type="submit" name="registro-ext"
        class="w-full text-white py-2 rounded-lg bg-[#328E6E] transition hover:bg-[#287256] shadow-lg">
        Registrar
      </button>
    </form>

    <div class="mt-4 text-center">
      <a href="./" class="text-sm text-gray-600 hover:underline">Ya tienes cuenta? Inicia Sesión aquí</a>
    </div>

    <div class="mt-6 flex justify-center">
      <img src="../img/logo-uda.png" alt="Logo Universidad de Atacama" class="h-12">
    </div>
  </div>

  <script src="../js/mensaje_registro.js"></script>
  <script src="../js/rut_validacion.js"></script>
</body>
</html>
