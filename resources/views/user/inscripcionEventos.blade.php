@php use Carbon\Carbon; @endphp

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Eventos Disponibles</title>
  @vite('resources/css/app.css')
  @vite('resources/js/app.js')
</head>
<body class="bg-gray-50">

  <div class="container mx-auto p-4">

    <h2 class="text-2xl font-bold text-center text-[#2D6A4F] mb-6">Eventos Disponibles</h2>

    <!-- ====== EVENTOS DIARIOS ====== -->
    <h3 class="text-xl font-semibold text-[#2D6A4F] mb-4">Eventos Diarios</h3>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
      @foreach($eventosDiarios as $evento)
        <div class="bg-white rounded-xl shadow-md p-4 flex flex-col justify-between">
          <h4 class="text-lg font-bold text-[#2D6A4F] mb-2">{{ $evento->titulo }}</h4>
          <p class="text-sm text-gray-700 line-clamp-3">{!! $evento->descripcion !!}</p>
          <button class="ver-mas-btn mt-3 bg-[#2D6A4F] text-white rounded-lg px-3 py-1 hover:bg-[#22543D] transition"
                  data-id="{{ $evento->id }}">
            Ver más
          </button>
        </div>

        <!-- Modal Diario -->
        <div id="modal-{{ $evento->id }}" class="fixed inset-0 hidden flex justify-center items-center z-50">
          <!-- Overlay -->
          <div class="absolute inset-0 bg-white/20 backdrop-blur-sm" data-modal-close></div>

          <!-- Contenido -->
          <div class="relative bg-white rounded-xl max-w-md w-full p-6 shadow-xl overflow-y-auto max-h-[90vh]">
            <h4 class="text-lg font-bold mb-2 text-[#2D6A4F]">{{ $evento->titulo }}</h4>
            <p class="text-sm text-gray-700 mb-2"><strong>Descripción:</strong></p>
            <div class="editor-content text-sm text-gray-600">{!! $evento->descripcion !!}</div>
            <button class="cerrar-modal-btn absolute top-2 right-2 text-gray-500 hover:text-red-500 text-xl"
                    data-modal-close>&times;</button>
          </div>
        </div>
      @endforeach
    </div>

    <!-- ====== EVENTOS SEMANALES ====== -->
    <h3 class="text-xl font-semibold text-[#2D6A4F] mt-10 mb-4">Eventos Semanales</h3>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
      @foreach($eventosSemanales as $evento)
        <div class="bg-white rounded-xl shadow-md p-4 flex flex-col justify-between">
          <h4 class="text-lg font-bold text-[#2D6A4F] mb-2">{{ $evento->titulo }}</h4>
          <p class="text-sm text-gray-700 line-clamp-3">{!! $evento->descripcion !!}</p>
          <button class="ver-mas-btn mt-3 bg-[#2D6A4F] text-white rounded-lg px-3 py-1 hover:bg-[#22543D] transition"
                  data-id="{{ $evento->id }}">
            Ver más
          </button>
        </div>

        <!-- Modal Semanal -->
        <div id="modal-{{ $evento->id }}" class="fixed inset-0 hidden flex justify-center items-center z-50">
          <!-- Overlay -->
          <div class="absolute inset-0 bg-white/20 backdrop-blur-sm" data-modal-close></div>

          <!-- Contenido -->
          <div class="relative bg-white rounded-xl max-w-md w-full p-6 shadow-xl overflow-y-auto max-h-[90vh]">
            <h4 class="text-lg font-bold mb-2 text-[#2D6A4F]">{{ $evento->titulo }}</h4>
            <p class="text-sm text-gray-700 mb-2"><strong>Descripción:</strong></p>
            <div class="editor-content text-sm text-gray-600">{!! $evento->descripcion !!}</div>
            <button class="cerrar-modal-btn absolute top-2 right-2 text-gray-500 hover:text-red-500 text-xl"
                    data-modal-close>&times;</button>
          </div>
        </div>
      @endforeach
    </div>

  </div>

  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const openButtons = document.querySelectorAll('.ver-mas-btn');
      openButtons.forEach(btn => {
        btn.addEventListener('click', () => {
          const modal = document.getElementById(`modal-${btn.dataset.id}`);
          if (modal) {
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
          }
        });
      });

      document.querySelectorAll('.cerrar-modal-btn, [data-modal-close]').forEach(el => {
        el.addEventListener('click', (e) => {
          const modal = e.target.closest('.fixed[id^="modal-"]');
          if (modal) {
            modal.classList.add('hidden');
            document.body.style.overflow = 'auto';
          }
        });
      });
    });
  </script>

</body>
</html>
