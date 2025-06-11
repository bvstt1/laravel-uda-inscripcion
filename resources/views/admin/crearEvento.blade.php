<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Crear Evento</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    @vite([
    'resources/css/app.css',
    'resources/css/ckeditor.css',
    'resources/js/ckeditor.js',
    'resources/js/app.js',
    ])
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="bg-white p-8 rounded-2xl shadow-md w-full max-w-lg lg:max-w-3xl">
        
        <div class="flex justify-end space-x-3 mb-6">
            <a href="{{ route('panel') }}"
            class="text-xs bg-[#328E6E] hover:bg-[#287256] text-white font-medium py-1 px-3 rounded-lg shadow">
                ← Volver al Panel
            </a>
            <a href="{{ route('eventos.index') }}" class="text-sm text-blue-600 hover:underline mr-2">Ver eventos</a>
            <a href="/logout" class="text-sm text-red-600 hover:underline">Cerrar sesión</a>
        </div>

        <h2 class="text-2xl font-bold text-center text-[#328E6E] mb-4">Crear Evento</h2>

        @if(session('success'))
            <div id="success-message" class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
            <script>
                setTimeout(() => {
                const msg = document.getElementById('success-message');
                if (msg) {
                    msg.style.opacity = '0';
                    setTimeout(() => msg.remove(), 500);
                }
                }, 3000);
            </script>
        @endif

        <form method="POST" action="{{ route('eventos.store') }}" class="space-y-4">
            @csrf

            <div>
                <label for="tipo" class="block font-medium text-gray-700">Tipo de evento</label>
                <select name="tipo" id="tipo" required class="mt-1 block w-full border border-gray-300 rounded-lg shadow-sm">
                    <option value="semanal">Semanal</option>
                    <option value="diario">Diario</option>
                </select>
            </div>

            <div id="semanaRelacionada">
                <label for="id_evento_padre" class="block font-medium text-gray-700">¿A que semana pertenece?</label>
                <select name="id_evento_padre" class="mt-1 block w-full border border-gray-300 rounded-lg shadow-sm">
                    <option value="">Ninguna semana asociada</option>
                    @foreach($semanales as $semana)
                        <option value="{{ $semana->id }}">{{ $semana->titulo }} - {{ $semana->fecha }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <input type="text" name="titulo" placeholder="Título del evento" required class="w-full p-2 border border-gray-300 rounded-lg">
            </div>

            <div class="relative">
                <input type="text" id="fecha" name="fecha" required placeholder="Seleccione una fecha" class="w-full p-2 pr-10 border border-gray-300 rounded-lg appearance-none">
                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M8 7V3M16 7V3M4 11H20M5 19H19A2 2 0 0021 17V7A2 2 0 0019 5H5A2 2 0 003 7V17A2 2 0 005 19Z" />
                    </svg>
                </div>
                <div id="info-fechas-semana" class="text-sm text-gray-600 hidden mt-1"></div>
            </div>

            <div>
                <input type="text" name="lugar" placeholder="Lugar del evento" required class="w-full p-2 border border-gray-300 rounded-lg">
            </div>

            <div id="horariosDiarios">
                <label class="block font-medium text-gray-700">Hora de inicio</label>
                <input type="time" name="hora" class="w-full p-2 border border-gray-300 rounded-lg mb-2">
                <label class="block font-medium text-gray-700">Hora de término</label>
                <input type="time" name="hora_termino" class="w-full p-2 border border-gray-300 rounded-lg">
            </div>

            <div id="editor-menu-bar" class="mb-2"></div>
            <textarea name="descripcion" id="editor" rows="4" class="w-full p-2 border border-gray-300 rounded-lg"></textarea>
            <div id="editor-word-count" class="text-sm text-right text-gray-600 mt-1"></div>

            <div>
                <label for="categoria_id" class="block font-medium text-gray-700">Categoría</label>
                <div class="flex gap-2">
                    <select name="categoria_id" id="categoria_id" class="flex-1 mt-1 block w-full border border-gray-300 rounded-lg shadow-sm">
                        <option value="">Sin categoría</option>
                        @foreach($categorias as $categoria)
                            <option value="{{ $categoria->id }}">{{ $categoria->nombre }}</option>
                        @endforeach
                    </select>
                    <button type="button" id="btnNuevaCategoria" class="mt-1 px-3 py-1 bg-blue-600 text-white rounded-lg text-sm hover:bg-blue-700">+</button>
                </div>
            </div>

            <button type="submit" class="w-full bg-[#328E6E] hover:bg-[#287256] text-white font-bold py-2 px-4 rounded-lg transition">
                Crear Evento
            </button>

        </form>

        
            <!-- Lista de Categorías existentes -->
            <div class="mt-6">
                <h3 class="text-lg font-semibold text-gray-700 mb-2">Categorías existentes</h3>
                <div class="space-y-2">
                    @foreach($categorias as $cat)
                    <div class="flex items-center justify-between bg-gray-100 rounded-lg px-4 py-2">
                        <div class="flex items-center gap-2">
                            <div class="w-5 h-5 rounded-full" style="background-color: {{ $cat->color }}"></div>
                            <span class="text-sm font-medium">{{ $cat->nombre }}</span>
                        </div>
                        <div class="flex gap-2">
                            <button type="button" onclick="editarCategoria({{ $cat->id }}, '{{ $cat->nombre }}', '{{ $cat->color }}')" class="text-blue-600 text-sm hover:underline">Editar</button>
                            <form action="{{ route('admin.categorias.destroy', $cat->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 text-sm hover:underline">Eliminar</button>
                            </form>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

        <div class="flex justify-center mt-4">
            <img src="{{ asset('img/logo-uda.png') }}" alt="Logo Universidad de Atacama" class="h-12">
        </div>

    </div>

    <!-- Modal Nueva Categoría -->
    <div id="modalCategoria" class="fixed inset-0 bg-[rgba(0,0,0,0.3)] flex items-center justify-center hidden z-50">
        <div class="bg-white p-6 rounded-xl shadow-xl w-full max-w-sm">
            <h3 class="text-xl font-bold mb-4">Crear nueva categoría</h3>
            <form id="formCategoria">
                <div class="mb-3">
                    <label class="block font-medium text-gray-700">Nombre</label>
                    <input type="text" name="nombre" required class="w-full p-2 border border-gray-300 rounded-lg">
                </div>
                <div class="mb-4">
                    <label class="block font-medium text-gray-700">Color (hex)</label>
                    <input type="color" name="color" class="w-16 h-10 p-0 border border-gray-300 rounded-lg">
                </div>
                <div class="flex justify-between">
                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                        Crear
                    </button>
                    <button type="button" id="btnCancelarCategoria" class="text-gray-600 hover:underline">Cancelar</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.getElementById('btnNuevaCategoria').addEventListener('click', () => {
            document.getElementById('modalCategoria').classList.remove('hidden');
        });

        document.getElementById('btnCancelarCategoria').addEventListener('click', () => {
            const modal = document.getElementById('modalCategoria');
            modal.classList.add('hidden');
            modal.querySelector('form').reset();
            modal.querySelector('form').removeAttribute('action');
            const methodInput = modal.querySelector('input[name="_method"]');
            if (methodInput) methodInput.remove();
        });

        document.getElementById('formCategoria').addEventListener('submit', async function (e) {
            e.preventDefault();
            const form = e.target;
            const formData = new FormData(form);

            const response = await fetch("{{ route('admin.categorias.store') }}", {
                method: "POST",
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: formData
            });

            const data = await response.json();

            if (response.ok) {
                const select = document.getElementById('categoria_id');
                const option = document.createElement('option');
                option.value = data.id;
                option.textContent = data.nombre;
                select.appendChild(option);
                select.value = data.id;

                form.reset();
                document.getElementById('modalCategoria').classList.add('hidden');
            } else {
                alert('Error al crear categoría');
            }
        });

        function editarCategoria(id, nombre, color) {
            const modal = document.getElementById('modalCategoria');
            modal.classList.remove('hidden');
            modal.querySelector('form').action = `/admin/categorias/${id}`;
            modal.querySelector('input[name="nombre"]').value = nombre;
            modal.querySelector('input[name="color"]').value = color;

            const methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = '_method';
            methodInput.value = 'PUT';
            modal.querySelector('form').appendChild(methodInput);
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="{{ asset('js/crearEvento.js') }}"></script>
</body>
</html>
