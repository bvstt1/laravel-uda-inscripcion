<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Totem de Asistencia</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/html5-qrcode@2.3.8/minified/html5-qrcode.min.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .pulse-border {
            box-shadow: 0 0 0 4px #328E6E44;
            animation: pulse 1.5s infinite;
        }
        @keyframes pulse {
            0% { box-shadow: 0 0 0 4px #328E6E44; }
            50% { box-shadow: 0 0 0 8px #328E6E22; }
            100% { box-shadow: 0 0 0 4px #328E6E44; }
        }
    </style>
</head>
<body class="bg-gray-100 h-screen flex items-center justify-center">

    <div class="bg-white shadow-xl rounded-lg p-8 w-full max-w-md text-center">
        
        <a href="{{ route('totem.selector') }}" class="absolute top-4 right-4 bg-[#328E6E] hover:bg-[#287256] text-white px-4 py-2 rounded transition text-sm">
            ← Volver
        </a>
        <h1 class="text-2xl font-bold text-[#328E6E] mb-4 mt-6">{{ $evento->titulo }}</h1>
        <p class="text-sm text-gray-600 mb-6">
            <strong>Fecha:</strong> {{ $evento->fecha }}<br>
            <strong>Horario:</strong> {{ $evento->hora }} - {{ $evento->hora_termino }}<br>
            <strong>Lugar:</strong> {{ $evento->lugar }}
        </p>

    @if(session('success'))
        <div id="success-message" class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
        <script>
            setTimeout(() => {
                const msg = document.getElementById('success-message');
                if (msg) {
                    msg.style.opacity = '0';
                    setTimeout(() => msg.remove(), 500); // Elimina tras desvanecerse
                }
            }, 2000); // Desaparece a los 3 segundos
        </script>
    @endif

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif

        <p id="mensaje-error" class="text-sm text-red-600 mt-2"></p>

        <!-- Escáner de QR -->
        <div id="scanner-container" class="mb-6">
            <div class="relative w-full h-64 border-4 border-[#328E6E] rounded-lg pulse-border overflow-hidden" id="qr-reader"></div>
            <p class="text-sm text-gray-500 mt-2">Acerca tu QR a la cámara para registrar asistencia automáticamente.</p>
        </div>

        <!-- Campo de RUT siempre visible -->
        <form id="asistencia-form" action="{{ secure_url(route('totem.registrar', $evento->id)) }}" method="POST" class="space-y-4">
            @csrf
            <input
                type="text"
                id="rut-input"
                name="rut"
                placeholder="También puedes escribir tu RUT"
                class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-[#328E6E] text-center text-lg"
                autofocus
            >
            <button
                type="submit"
                class="w-full bg-[#328E6E] hover:bg-[#287256] text-white font-semibold py-2 px-4 rounded-lg transition"
            >
                Registrar Asistencia
            </button>
        </form>
    </div>

    <audio id="beep" src="https://www.soundjay.com/button/beep-07.mp3" preload="auto"></audio>

    @php
        $cierreTimestamp = \Carbon\Carbon::parse($evento->fecha . ' ' . $evento->hora_termino)
                            ->subMinutes(15)
                            ->format('Y-m-d H:i:s');
    @endphp

<script>
    const cierreTimestamp = new Date("{{ $cierreTimestamp }}").getTime();
    setInterval(() => {
        if (new Date().getTime() >= cierreTimestamp) {
            window.location.reload();
        }
    }, 30000);

    const beep = document.getElementById("beep");
    const qrReader = new Html5Qrcode("qr-reader");
    const config = { fps: 10, qrbox: 200 };

    function onScanSuccess(decodedText) {
        document.getElementById("rut-input").value = decodedText;
        document.getElementById("asistencia-form").submit();
        beep.play();
        qrReader.stop();
    }

    Html5Qrcode.getCameras().then(devices => {
        if (devices.length > 0) {
            const cameraId = devices[0].id;
            qrReader.start(cameraId, config, onScanSuccess);
        } else {
            document.getElementById("mensaje-error").textContent = "No se encontraron cámaras disponibles.";
        }
    }).catch(err => {
        console.error("Error al obtener cámaras:", err);
        document.getElementById("mensaje-error").textContent = "No se pudo acceder a la cámara. Verifica los permisos del navegador.";
    });
</script>
</body>
</html>
