<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Mi Cuenta</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen py-10 px-4">
  <div class="max-w-xl mx-auto bg-white p-6 rounded-xl shadow-md">
    <div class="flex justify-between items-center mb-6">
      <a href="{{ route('inscripcionEventos') }}" class="text-sm text-blue-600 hover:underline">&larr; Volver</a>
      <a href="/logout" class="text-sm text-red-600 hover:underline">Cerrar sesión</a>
    </div>

    <h1 class="text-2xl font-bold text-[#328E6E] mb-4">Editar Cuenta</h1>

    @if (session('success'))
      <div class="bg-green-100 text-green-700 border border-green-400 rounded p-3 mb-4">
        {{ session('success') }}
      </div>
    @endif

    @if ($errors->any())
      <div class="bg-red-100 text-red-700 border border-red-400 rounded p-3 mb-4">
        <ul class="list-disc list-inside text-sm">
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <form action="{{ route('cuenta.actualizar') }}" method="POST" class="space-y-4">
      @csrf
      <div>
        <label for="rut" class="block text-sm font-medium text-gray-700">RUT</label>
        <input type="text" id="rut" name="rut" value="{{ $usuario->rut }}" readonly
               class="w-full border border-gray-300 rounded px-4 py-2 bg-gray-100 text-gray-500 cursor-not-allowed">
      </div>

      @php $esEstudiante = session('usuario.tipo') === 'estudiante'; @endphp

      @if (!$esEstudiante)
        <div>
          <label for="correo" class="block text-sm font-medium text-gray-700">Correo</label>
          <input type="email" id="correo" name="correo" value="{{ $usuario->correo }}" required
                class="w-full border border-gray-300 rounded px-4 py-2">
          <p id="mensaje-correo" class="text-sm text-red-600 hidden mt-1"></p>
        </div>

        <div>
          <label for="cargo" class="block text-sm font-medium text-gray-700">Cargo</label>
          <input type="text" id="cargo" name="cargo" value="{{ $usuario->cargo }}" required
                class="w-full border border-gray-300 rounded px-4 py-2">
        </div>

        <div>
          <label for="institucion" class="block text-sm font-medium text-gray-700">Instituci&oacute;n</label>
          <input type="text" id="institucion" name="institucion" value="{{ $usuario->institucion }}" required
                class="w-full border border-gray-300 rounded px-4 py-2">
        </div>
      @endif

      <div>
        <label for="contrasena" class="block text-sm font-medium text-gray-700">Nueva contrase&ntilde;a</label>
        <input type="password" id="contrasena" name="contrasena"
              class="w-full border border-gray-300 rounded px-4 py-2">
        <p id="mensaje-contrasena" class="text-sm text-red-600 mt-1 hidden"></p>
      </div>

      <div>
        <label for="contrasena_confirmation" class="block text-sm font-medium text-gray-700">Confirmar contrase&ntilde;a</label>
        <input type="password" id="contrasena_confirmation" name="contrasena_confirmation"
              class="w-full border border-gray-300 rounded px-4 py-2">
        <p id="mensaje-confirmacion-js" class="text-sm text-red-600 mt-1 hidden"></p>   
      </div>

      <button type="submit"
              class="w-full bg-[#328E6E] text-white font-semibold py-2 px-4 rounded hover:bg-[#287256] transition">
        Guardar cambios
      </button>
    </form>

    <hr class="my-6">

    <form action="{{ route('cuenta.eliminar') }}" method="POST" onsubmit="return confirm('Esta acci&oacute;n eliminar&aacute; permanentemente tu cuenta. &iquest;Est&aacute;s seguro?');">
      @csrf
      <button type="submit"
              class="w-full bg-red-600 text-white font-semibold py-2 px-4 rounded hover:bg-red-700 transition">
        Eliminar cuenta permanentemente
      </button>
    </form>
  </div>

  <script src="{{ asset('js/vistaRut.js') }}"></script>
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
