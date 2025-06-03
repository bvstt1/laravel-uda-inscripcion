<form method="POST" action="/admin/eventos/1"> {{-- reemplaza el 1 por un ID válido --}}
    @csrf
    @method('PUT')
    <input type="text" name="titulo" value="Título de prueba">
    <button type="submit">Probar envío directo</button>
</form>
