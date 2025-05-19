<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Eventos Disponibles</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen font-sans">
  <div class="max-w-4xl mx-auto py-10 px-6">
    <div class="flex justify-between items-center mb-8">
      <h1 class="text-3xl font-bold text-[#328E6E]">Eventos Disponibles</h1>
      <a href="/logout" class="text-sm text-red-600 hover:underline">Cerrar Sesión</a>
    </div>

    <!-- Eventos Diarios -->
    <section class="mb-10">
      <h2 class="text-xl font-semibold text-gray-800 mb-4">Eventos Diarios</h2>
      <!-- Aquí se deben iterar los eventos diarios -->
      <div class="space-y-6">
        <!-- Ejemplo de evento diario -->
        <div class="bg-white rounded-xl shadow-md p-6">
          <h3 class="text-lg font-semibold text-[#328E6E]">Título del Evento Diario</h3>
          <p class="text-sm text-gray-600"><strong>Fecha:</strong> 2025-06-01</p>
          <p class="text-sm text-gray-600"><strong>Hora:</strong> 15:00</p>
          <p class="text-sm text-gray-600"><strong>Lugar:</strong> Auditorio A</p>
          <p class="text-sm text-gray-700 mt-2">Descripción del evento diario no asociado a ninguna semana.</p>
          <div class="mt-4">
            <!-- Mostrar Inscribirse o Desinscribirse según estado -->
            <button class="bg-[#328E6E] hover:bg-[#287256] text-white py-1 px-4 rounded-lg transition">Inscribirse</button>
          </div>
        </div>
      </div>
    </section>

    <!-- Eventos Semanales -->
    <section>
      <h2 class="text-xl font-semibold text-gray-800 mb-4">Eventos Semanales</h2>
      <!-- Aquí se deben iterar los eventos semanales -->
      <div class="space-y-6">
        <!-- Ejemplo de evento semanal -->
        <div class="bg-white rounded-xl shadow-md p-6">
          <h3 class="text-lg font-semibold text-green-700">Título del Evento Semanal</h3>
          <p class="text-sm text-gray-600"><strong>Semana:</strong> Desde 2025-06-03 al 2025-06-07</p>
          <p class="text-sm text-gray-600"><strong>Hora:</strong> 17:00</p>
          <p class="text-sm text-gray-600"><strong>Lugar:</strong> Sala B</p>
          <p class="text-sm text-gray-700 mt-2">Este es un evento semanal. Al hacer clic, podrás ver y elegir uno de sus días disponibles.</p>
          <div class="mt-4">
            <a href="./inscripcion_eventos_semana.php?id_evento=123" class="text-sm text-green-700 hover:underline">Ver días disponibles</a>
          </div>
        </div>
      </div>
    </section>
  </div>
  <script src="../../js/confirmarInscripcion.js"></script>
</body>
</html>
