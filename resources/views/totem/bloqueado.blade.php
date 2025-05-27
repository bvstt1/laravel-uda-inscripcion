<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro de asistencia bloqueado</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-red-100 min-h-screen flex items-center justify-center px-4">
    <div class="bg-white shadow-lg rounded-xl p-8 max-w-md text-center">
        <h1 class="text-3xl font-bold text-red-600 mb-4">Asistencia bloqueada</h1>
        <p class="text-gray-700 mb-6">
            El registro de asistencia para el evento <strong>{{ $evento->titulo }}</strong> ha sido cerrado porque quedan menos de 15 minutos para su finalización.
        </p>

        <div class="mb-4 text-sm text-gray-500">
            <p><strong>Fecha:</strong> {{ $evento->fecha }}</p>
            <p><strong>Hora de término:</strong> {{ $evento->hora_termino }}</p>
        </div>

        <a href="{{ route('totem.selector') }}" class="inline-block bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded transition">
            ← Volver a selección de eventos
        </a>
    </div>
</body>
</html>
