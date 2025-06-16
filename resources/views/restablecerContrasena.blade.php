<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Restablecer contraseña</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
  <div class="bg-white rounded-2xl shadow-lg p-8 w-full max-w-sm">

    {{-- ✅ MENSAJE DE CONTRASEÑA CAMBIADA --}}
    @if (session('contrasena_cambiada'))
      <div id="mensaje-exito" class="bg-green-100 text-green-700 text-sm rounded p-4 text-center mb-4 transition-opacity duration-500">
        <p class="mb-2 font-semibold">¡Contraseña cambiada exitosamente!</p>
        <p>Serás redirigido al inicio de sesión en <span id="contador">5</span> segundos.</p>
        <a href="/login" class="inline-block mt-4 bg-[#328E6E] text-white px-4 py-2 rounded hover:bg-[#287256] transition">
          Ir ahora
        </a>
      </div>

      <script>
        let segundos = 5;
        const contador = document.getElementById('contador');
        const intervalo = setInterval(() => {
          segundos--;
          if (segundos <= 0) {
            clearInterval(intervalo);
            window.location.href = "/login";
          } else {
            contador.textContent = segundos;
          }
        }, 1000);
      </script>
    @endif

    {{-- ❌ MENSAJES DE ERROR --}}
    @if ($errors->any())
      <div class="bg-red-100 text-red-700 text-sm rounded p-2 mb-4 text-center">
        {{ $errors->first() }}
      </div>
    @endif

    {{-- 📝 FORMULARIO DE RESTABLECIMIENTO --}}
    @if (!session('contrasena_cambiada'))
      <h2 class="text-2xl font-bold text-center text-[#328E6E] mb-4">Restablecer contraseña</h2>

      <form method="POST" action="{{ route('password.update') }}" class="space-y-4">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">

        <input type="email" name="email" placeholder="Correo electrónico" required value="{{ old('email') }}"
          class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#328E6E]">

        <input type="password" id="contrasena" name="password" placeholder="Nueva contraseña" required
          class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#328E6E]">
        <p id="mensaje-contrasena" class="text-sm text-red-600 hidden"></p>

        <input type="password" id="contrasena_confirmation" name="password_confirmation" placeholder="Confirmar contraseña" required
          class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#328E6E]">
        <p id="mensaje-confirmacion-js" class="text-sm text-red-600 hidden"></p>

        <button type="submit"
          class="w-full bg-[#328E6E] text-white py-2 rounded-lg hover:bg-[#287256] transition shadow-lg">
          Guardar nueva contraseña
        </button>
      </form>

      <div class="mt-4 text-center">
        <a href="/login" class="text-sm text-gray-600 hover:underline">← Volver al inicio de sesión</a>
      </div>
    @endif

  </div>

  {{-- Validaciones JS --}}
  <script src="{{ asset('js/validacionLoginRegistro.js') }}"></script>
</body>
</html>
