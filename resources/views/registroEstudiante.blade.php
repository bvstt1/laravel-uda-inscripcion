<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Registro Estudiante UDA</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
  <div class="bg-white rounded-2xl shadow-lg p-8 w-full max-w-sm">
    <h2 class="text-2xl font-bold text-center text-[#328E6E] mb-1">Crear cuenta</h2> 
    <p class="text-center text-md text-gray-700 mb-6">
      Estudiante <span class="text-[#328E6E] font-semibold">UDA</span>
    </p>

    <form action="/registroEstudiante" method="POST" onsubmit="return validarFormulario();" class="space-y-4">
      @csrf 
      <input type="text" id="rut" name="rut" maxlength="10" placeholder="RUT" oninput="soloNumeros(this)" required
        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#328E6E]">

      <input type="email" name="correo" placeholder="correo@alumnos.uda.cl" required
        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#328E6E]">

      <input type="password" name="contrasena" placeholder="Contraseña" required
        class="w-full border border-gray-200 bg-gray-100 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#328E6E]">

      <input type="password" name="contrasena_confirmation" placeholder="Confirmar Contraseña" required
        class="w-full border border-gray-200 bg-gray-100 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#328E6E]">

      <select name="carrera" required
        class="w-full border border-gray-300 rounded-lg px-4 py-2 bg-gray-100 focus:outline-none focus:ring-2 focus:ring-[#328E6E]">
        <option value="">Seleccionar Carrera</option>
        <optgroup label="Facultad de Ingeniería">
          <option value="Ingeniería Civil en Minas">Ingeniería Civil en Minas</option>
          <option value="Ingeniería Civil en Metalurgia">Ingeniería Civil en Metalurgia</option>
          <option value="Ingeniería Civil en Computación e Informatica">Ingeniería Civil en Computación e Informatica</option>
          <option value="Ingeniería Comercial">Ingeniería Comercial</option>
          <option value="Geoloía">Geología</option>
          <option value="Ingeniería Civil Industrial">Ingeniería Civil Industrial</option>
        </optgroup>
        <optgroup label="Facultad de Humanidades y Educación">
          <option value="Licenciatura en Educación y Pedagogía en Educación General Básica">Licenciatura en Educación y Pedagogía en Educación General Básica</option>
          <option value="Licenciatura en Educación y Pedagogía en Educación Física">Licenciatura en Educación y Pedagogía en Educación Física</option>
          <option value="Licenciatura en Educación y Pedagogía en Educación Parvularia">Licenciatura en Educación y Pedagogía en Educación Parvularia</option>
          <option value="Licenciatura en Educación y Pedagogía en Inglés">Licenciatura en Educación y Pedagogía en Inglés</option>
          <option value="Traductología y Traductor e Interprete Inglés-Español">Traductología y Traductor e Interprete Inglés-Español</option>
          <option value="Psicología">Psicología</option>
        </optgroup>
        <optgroup label="Facultad de Ciencias Jurídicas y Sociales">
          <option value="Derecho">Derecho</option>
          <option value="Trabajo Social">Trabajo Social</option>
        </optgroup>
        <optgroup label="Fac. de Ciencias de la Salud">
          <option value="Enfermería">Enfermería</option>
          <option value="Kinesiología">Kinesiología</option>
          <option value="Obstetricia y Puericultura">Obstetricia y Puericultura</option>
          <option value="Nutrición y Dietética">Nutrición y Dietética</option>
        </optgroup>
        <optgroup label="Facultad de Medicina">
          <option value="Medicina">Medicina</option>
        </optgroup>
        <optgroup label="Facultad Tecnológica">
          <option value="Construcción Civil">Construcción Civil</option>
        </optgroup>
      </select>

      <button type="submit" name="registro-est"
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

  <script src="../js/mensaje_registro.js"></script>
  <script src="../js/rut_validacion.js"></script>
</body>
</html>
