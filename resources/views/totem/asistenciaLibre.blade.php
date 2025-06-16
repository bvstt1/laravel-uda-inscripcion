<!-- resources/views/totem/asistenciaLibre.blade.php -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Asistencia Libre</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 h-screen flex items-center justify-center">
    <div class="bg-white shadow-xl rounded-lg p-8 w-full max-w-md text-center">
        <h1 class="text-2xl font-bold text-[#328E6E] mb-6">Totem de Asistencia</h1>
        <p class="text-sm text-gray-600 mb-6">Ingresa tu RUT para registrar tu asistencia del día de hoy a las instalaciones.</p>

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
                }, 2000); // Desaparece a los 2 segundos
            </script>
        @endif

        @if(session('error'))
            <div id="error-message" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                {{ session('error') }}
            </div>
            <script>
                setTimeout(() => {
                    const errorMsg = document.getElementById('error-message');
                    if (errorMsg) {
                        errorMsg.style.opacity = '0';
                        setTimeout(() => errorMsg.remove(), 500); // Elimina tras desvanecerse
                    }
                }, 3000); // Desaparece a los 3 segundos
            </script>
        @endif

        <form action="{{ route('totem.registro.libre') }}" method="POST" class="space-y-4">
            @csrf
            <input
                type="text"
                name="rut"
                placeholder="Ingresa tu RUT"
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
    <script>
    setTimeout(() => {
        const msg = document.getElementById('error-message');
        if (msg) {
            msg.style.opacity = '0';
            setTimeout(() => msg.remove(), 500);
        }
    }, 3000);
</script>
</body>
</html>
