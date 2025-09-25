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
<body class="bg-gray-50 min-h-screen flex justify-center py-12 px-4">

  <div class="w-full max-w-3xl bg-white rounded-2xl shadow-lg p-8 space-y-10">

    <!-- Header -->
    <div class="flex justify-between items-center">
      <h1 class="text-3xl font-bold text-[#328E6E]">Mi Cuenta</h1>
      <div class="flex gap-2">
        <a href="{{ route('inscripcionEventos') }}" class="px-3 py-1 bg-emerald-100 text-emerald-800 rounded-md hover:bg-emerald-200 transition">← Volver</a>
      </div>
    </div>

    <!-- Mensajes -->
    @if (session('success'))
      <div class="bg-green-100 border border-green-300 text-green-700 rounded-lg px-4 py-3">
        {{ session('success') }}
      </div>
    @endif

    @if ($errors->any())
      <div class="bg-red-100 border border-red-300 text-red-700 rounded-lg px-4 py-3">
        <ul class="list-disc list-inside text-sm">
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <!-- Sección: Datos Personales -->
    <section class="space-y-6">
      <h2 class="text-xl font-semibold text-gray-700 border-b pb-2 mb-4">Ver Datos Personales</h2>
      <form action="{{ route('cuenta.actualizar') }}" method="POST" class="space-y-4">
        @csrf

        <!-- RUT (solo lectura) -->
        <div>
          <label for="rut" class="block text-sm font-semibold text-gray-700">RUT</label>
          <input type="text" id="rut" name="rut" value="{{ number_format(substr($usuario->rut, 0, -1), 0, '', '.') . '-' . substr($usuario->rut, -1) }}" readonly
                class="w-full border border-gray-300 rounded-xl px-4 py-2 bg-gray-100 text-gray-500 cursor-not-allowed">
        </div>

        <!-- Correo (solo lectura) -->
        <div class="mt-4">
          <label for="correo" class="block text-sm font-semibold text-gray-700">Correo</label>
          <input type="email" id="correo" name="correo" value="{{ $usuario->correo ?? $usuario->email }}" readonly
                class="w-full border border-gray-300 rounded-xl px-4 py-2 bg-gray-100 text-gray-500 cursor-not-allowed">
        </div>

        <!-- Nombre -->

        <!-- Solo si NO es estudiante -->
        @php $esEstudiante = session('usuario.tipo') === 'estudiante'; @endphp
        @if (!$esEstudiante)
          <div>
            <label for="correo" class="block text-sm font-semibold text-gray-700">Correo</label>
            <input type="email" id="correo" name="correo" value="{{ $usuario->correo }}" required
                   oninput="validarCorreo(this)"
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

        <!-- Solo mostrar botón Guardar cambios si NO es estudiante -->
        @if (!$esEstudiante)
          <button type="submit"
              class="w-full bg-[#328E6E] text-white py-2 rounded-lg hover:bg-[#287256] transition shadow-lg transform hover:scale-[1.02]">
            Guardar cambios
          </button>
        @endif
      </form>
    </section>

    <!-- Sección: Cambiar Contraseña -->
    <section class="space-y-6">
      <h2 class="text-xl font-semibold text-gray-700 border-b pb-2 mb-4">Cambiar Contraseña</h2>
      <form action="{{ route('cuenta.actualizar') }}" method="POST" class="space-y-4">
        @csrf

        <div>
          <label for="contrasena" class="block text-sm font-semibold text-gray-700">Nueva contraseña</label>
          <input type="password" id="contrasena" name="contrasena"
                 oninput="validarContrasena(this)"
                 class="w-full border border-gray-300 rounded-xl px-4 py-2 focus:ring-2 focus:ring-[#328E6E] focus:outline-none">
          <p id="mensaje-contrasena" class="text-sm text-red-600 hidden mt-1"></p>
        </div>

        <div>
          <label for="contrasena_confirmation" class="block text-sm font-semibold text-gray-700">Confirmar contraseña</label>
          <input type="password" id="contrasena_confirmation" name="contrasena_confirmation"
                 oninput="validarConfirmacionContrasena()"
                 class="w-full border border-gray-300 rounded-xl px-4 py-2 focus:ring-2 focus:ring-[#328E6E] focus:outline-none">
          <p id="mensaje-confirmacion-js" class="text-sm text-red-600 hidden mt-1"></p>
        </div>

        <button type="submit"
        class="w-full bg-[#328E6E] text-white py-2 rounded-lg hover:bg-[#287256] transition shadow-lg transform hover:scale-[1.02]">
          Cambiar contraseña
        </button>
      </form>
    </section>

    <!-- Sección: Eliminar cuenta -->
    <section class="space-y-4">
      <h2 class="text-xl font-semibold text-gray-700 border-b pb-2 mb-4">Acciones críticas</h2>
      <form action="{{ route('cuenta.eliminar') }}" method="POST"
      onsubmit="return confirm('Esta acción eliminará permanentemente tu cuenta. ¿Estás seguro?');">
          @csrf
          @method('DELETE') <!-- Aquí indicamos que realmente es DELETE -->
          <button type="submit"
                  class="w-full bg-red-500 text-white font-semibold py-2 px-4 rounded-xl hover:bg-red-700 transition shadow-sm">
              Eliminar cuenta permanentemente
          </button>
      </form>

    </section>

  </div>

  <script src="{{ asset('js/validacionLoginRegistro.js') }}"></script>
  <script>
    // Listeners para validación en tiempo real
    document.addEventListener('DOMContentLoaded', function () {
      const tipoUsuario = "{{ session('usuario.tipo') ?? '' }}";

      const nombre = document.getElementById('nombre');
      if (nombre) nombre.addEventListener('input', () => validarNombreApellido(nombre));

      const apellido = document.getElementById('apellido');
      if (apellido) apellido.addEventListener('input', () => validarNombreApellido(apellido));

      const correo = document.getElementById('correo');
      if (correo) correo.addEventListener('input', () => validarCorreo(correo));

      const contrasena = document.getElementById('contrasena');
      const confirmar = document.getElementById('contrasena_confirmation');
      if (contrasena) contrasena.addEventListener('input', () => validarContrasena(contrasena));
      if (confirmar) confirmar.addEventListener('input', validarConfirmacionContrasena);
    });
  </script>
</body>
</html>
