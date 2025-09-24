<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Recuperar Contraseña</title>
  @vite('resources/css/app.css')
  @vite('resources/js/app.js')
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
  <div class="bg-white rounded-2xl shadow-lg p-8 w-full max-w-sm">
    <h2 class="text-2xl font-bold text-center text-[#328E6E] mb-2">¿Olvidaste tu contraseña?</h2>
    <p class="text-center text-sm text-gray-600 mb-6">
      Ingresa tu correo registrado y te enviaremos un enlace para restablecerla.
    </p>

    @if (session('status'))
      <div id="success-message" class="bg-green-100 text-green-700 text-sm rounded p-2 mb-4 text-center">
        {{ session('status') }}
      </div>
      <script>
        setTimeout(() => {
          const msg = document.getElementById('success-message');
          if (msg) {
            msg.style.opacity = '0';
            setTimeout(() => msg.remove(), 500);
          }
        }, 3000);
      </script>
    @endif

    @if ($errors->any())
      <div id="error-message" class="bg-red-100 text-red-700 text-sm rounded p-2 mb-4 text-center transition-opacity duration-500">
        {{ $errors->first() }}
      </div>
      <script>
        setTimeout(() => {
          const msg = document.getElementById('error-message');
          if (msg) {
            msg.style.opacity = '0';
            setTimeout(() => msg.remove(), 500);
          }
        }, 3000);
      </script>
    @endif

    <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
      @csrf

      <select name="tipo" required class="w-full border border-gray-300 rounded-lg px-4 py-2">
        <option value="">Selecciona tu tipo de usuario</option>
        <option value="estudiante">Estudiante</option>
        <option value="externo">Externo</option>
      </select>

      <input type="email" name="email" placeholder="Correo electrónico" required
        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#328E6E]">

      <button type="submit"
        class="w-full bg-[#328E6E] text-white py-2 rounded-lg hover:bg-[#287256] transition shadow-lg">
        Enviar enlace de recuperación
      </button>
    </form>


    <div class="mt-4 text-center">
      <a href="/login" class="text-sm text-gray-600 hover:underline">← Volver al inicio de sesión</a>
    </div>
  </div>
</body>
</html>
