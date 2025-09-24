<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Mi Cuenta</title>
  @vite('resources/css/app.css')
  @vite('resources/js/app.js')
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
  <style>
    body { font-family: 'Inter', sans-serif; }
  </style>
</head>
<body class="bg-gray-50 min-h-screen flex items-center justify-center py-12 px-4">
  <div class="w-full max-w-xl bg-white p-8 rounded-2xl shadow-lg">
    <!-- Header -->
    <div class="flex justify-between items-center mb-8">
      <h1 class="text-3xl font-bold text-[#328E6E]">Mi Cuenta</h1>
      <div class="flex gap-2">
        <a href="{{ route('inscripcionEventos') }}" class="px-3 py-1 bg-emerald-100 text-emerald-800 rounded-md hover:bg-emerald-200 transition">← Volver</a>
        <a href="/logout" class="px-3 py-1 bg-red-100 text-red-700 rounded-md hover:bg-red-200 transition">Cerrar sesión</a>
      </div>
    </div>

    <!-- Mensajes -->
    @if (session('success'))
      <div class="bg-green-100 border border-green-300 text-green-700 rounded-lg px-4 py-3 mb-4">
        {{ session('success') }}
      </div>
    @endif

    @if ($errors->any())
      <div class="bg-red-100 border border-red-300 text-red-700 rounded-lg px-4 py-3 mb-4">
        <ul class="list-disc list-inside text-sm">
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <!-- Formulario -->
    <form action="{{ route('cuenta.actualizar') }}" method="POST" class="space-y-6">
      @csrf
      <div>
        <label for="rut" class="block text-sm font-semibold text-gray-700">RUT</label>
        <input type="text" id="rut" name="rut" value="{{ $usuario->rut }}" readonly
               class="w-full border border-gray-300 rounded-xl px-4 py-2 bg-gray-100 text-gray-500 cursor-not-allowed">
      </div>

      @php $esEstudiante = session('usuario.tipo') === 'estudiante'; @endphp
      @if (!$esEstudiante)
        <div>
          <label for="correo" class="block text-sm font-semibold text-gray-700">Correo</label>
          <input type="email" id="correo" name="correo" value="{{ $usuario->correo }}" required
                 class="w-full border border-gray-300 rounded-xl px-4 py-2 focus:ring-2 focus:ring-[#328E6E] focus:outline-none">
          <p id="mensaje-correo" class="text-sm text-red-600 hidden mt-1"></p>
        </div>

        <div>
          <label for="cargo" class="block text-sm font-semibold text-gray-700">Cargo</label>
          <input type="text" id="cargo" name="cargo" value="{{ $usuario->cargo }}" required
                 class="w-full border border-gray-300 rounded-xl px-4 py-2 focus:ring-2 focus:ring-[#328E6E] focus:outline-none">
        </div>

        <div>
          <label for="institucion" class="block text-sm font-semibold text-gray-700">Institución</label>
          <input type="text" id="institucion" name="institucion" value="{{ $usuario->institucion }}" required
                 class="w-full border border-gray-300 rounded-xl px-4 py-2 focus:ring-2 focus:ring-[#328E6E] focus:outline-none">
        </div>
      @endif

      <div>
        <label for="contrasena" class="block text-sm font-semibold text-gray-700">Nueva contraseña</label>
        <input type="password" id="contrasena" name="contrasena"
               class="w-full border border-gray-300 rounded-xl px-4 py-2 focus:ring-2 focus:ring-[#328E6E] focus:outline-none">
        <p id="mensaje-contrasena" class="text-sm text-red-600 mt-1 hidden"></p>
      </div>

      <div>
        <label for="contrasena_confirmation" class="block text-sm font-semibold text-gray-700">Confirmar contraseña</label>
        <input type="password" id="contrasena_confirmation" name="contrasena_confirmation"
               class="w-full border border-gray-300 rounded-xl px-4 py-2 focus:ring-2 focus:ring-[#328E6E] focus:outline-none">
        <p id="mensaje-confirmacion-js" class="text-sm text-red-600 mt-1 hidden"></p>   
      </div>

      <button type="submit"
              class="w-full bg-[#328E6E] text-white font-semibold py-2 px-4 rounded-xl hover:bg-[#287256] transition shadow-sm">
        Guardar cambios
      </button>
    </form>

    <hr class="my-8 border-gray-200">

    <form action="{{ route('cuenta.eliminar') }}" method="POST" onsubmit="return confirm('Esta acción eliminará permanentemente tu cuenta. ¿Estás seguro?');">
      @csrf
      <button type="submit"
              class="w-full bg-red-600 text-white font-semibold py-2 px-4 rounded-xl hover:bg-red-700 transition shadow-sm">
        Eliminar cuenta permanentemente
      </button>
    </form>
  </div>

  <script src="{{ asset('js/validacionLoginRegistro.js') }}"></script>
  <script>
    const tipoUsuario = "{{ session('usuario.tipo') ?? '' }}";

    const correoInput = document.getElementById('correo');
    if (correoInput && tipoUsuario === 'estudiante') {
      correoInput.addEventListener('input', () => validarCorreo(correoInput));
    }

    const contrasenaInput = document.getElementById('contrasena');
    const confirmarInput = document.getElementById('contrasena_confirmation');
    if (contrasenaInput) contrasenaInput.addEventListener('input', () => validarContrasena(contrasenaInput));
    if (confirmarInput) confirmarInput.addEventListener('input', validarConfirmacionContrasena);
  </script>
</body>
</html>
