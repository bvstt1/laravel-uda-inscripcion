<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seleccionar Evento</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen py-12 px-4">
    <div class="max-w-4xl mx-auto">
        <a href="{{ route('panel') }}" class="absolute top-4 right-4 bg-[#328E6E] hover:bg-[#287256] text-white px-4 py-2 rounded transition text-sm">
            ← Volver al panel
        </a>
        <h1 class="text-3xl font-bold text-[#328E6E] mb-8 text-center">Selecciona un evento para registrar asistencia</h1>

        @if($eventos->isEmpty())
            <p class="text-gray-600 text-center">No hay eventos disponibles en este momento.</p>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @foreach($eventos as $evento)
                @php
                    $terminaEn = \Carbon\Carbon::parse($evento->fecha . ' ' . $evento->hora_termino)->subMinutes(15);
                    $bloqueado = now()->greaterThanOrEqualTo($terminaEn);                
                @endphp

                <div class="bg-white shadow rounded-lg p-6 text-center">
                    <h2 class="text-xl font-semibold text-[#328E6E] mb-2">{{ $evento->titulo }}</h2>
                    <p class="text-sm text-gray-600">
                        <strong>Fecha:</strong> {{ $evento->fecha }}<br>
                        <strong>Hora:</strong> {{ $evento->hora }} - {{ $evento->hora_termino }}<br>
                        <strong>Lugar:</strong> {{ $evento->lugar }}
                    </p>

                    @if ($bloqueado)
                        <a href="{{ route('totem.form', $evento->id) }}" class="mt-4 inline-block bg-red-500 text-white px-4 py-2 rounded transition cursor-not-allowed">
                            Evento bloqueado
                        </a>
                    @else
                        <a href="{{ route('totem.form', $evento->id) }}" class="mt-4 inline-block bg-[#328E6E] hover:bg-[#287256] text-white px-4 py-2 rounded transition">
                            Ingresar al Tótem
                        </a>
                    @endif
                </div>
            @endforeach

            </div>
        @endif
    </div>
</body>
</html>
