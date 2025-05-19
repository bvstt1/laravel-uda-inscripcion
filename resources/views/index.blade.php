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
    <form action="../php/login.php" method="POST" onsubmit="return validarFormulario();" class="space-y-4">
      <input type="text" id="rut" name="rut" placeholder="RUT" oninput="soloNumeros(this)" required
        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#328E6E]">
      <input type="email" id="correo" name="correo" placeholder="Correo" required
        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#328E6E]">
      <input type="password" id="contrasena" name="contrasena" placeholder="Contraseña" required
        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#328E6E]">
      <div class="text-right">
        <a href="#" class="text-sm text-[#328E6E] hover:underline">¿Has olvidado tu contraseña?</a>
      </div>
      <button type="submit" name="login"
        class="w-full bg-[#328E6E] text-white py-2 rounded-lg hover:bg-[#287256] transition shadow-lg">
        Iniciar Sesión
      </button>
    </form>
    <div class="mt-4 text-center">
      <a href="./userSelection" class="text-sm text-gray-600 hover:underline">Crear una nueva cuenta</a>
    </div>
    <div class="mt-6 flex justify-center">
      <img src="../img/logo-uda.png" alt="Logo Universidad de Atacama" class="h-12">
    </div>
  </div>
  <script src="../js/rut_validacion.js"></script>
</body>
</html>
