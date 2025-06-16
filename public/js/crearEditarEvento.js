document.addEventListener("DOMContentLoaded", function () {

    // ==========================================================
    // ELEMENTOS CLAVE
    // ==========================================================
    const tipoSelect = document.getElementById("tipo");
    const eventoPadre = document.querySelector('select[name="id_evento_padre"]');
    const fechaInput = document.getElementById("fecha");
    const infoFechas = document.getElementById("info-fechas-semana");
    const semanaRelacionada = document.getElementById('semanaRelacionada');
    const horariosDiarios = document.getElementById('horariosDiarios');
    const form = document.querySelector('form');
    let flatpickrInstance;

    // ==========================================================
    // INICIALIZAR FLATPICKR
    // ==========================================================
    function inicializarFlatpickr(config = {}) {
        if (flatpickrInstance) flatpickrInstance.destroy();
        flatpickrInstance = flatpickr(fechaInput, {
            dateFormat: "Y-m-d",
            ...config
        });
    }

    // ==========================================================
    // LÓGICA SEGÚN TIPO DE EVENTO (Semanal o Diario)
    // ==========================================================
    function toggleCampos() {
        const esSemanal = tipoSelect.value === 'semanal';

        semanaRelacionada.style.display = esSemanal ? 'none' : 'block';
        horariosDiarios.style.display = esSemanal ? 'none' : 'block';

        if (esSemanal) {
            horariosDiarios.querySelectorAll('input').forEach(input => input.value = '');
            inicializarFlatpickr({ enable: [date => date.getDay() === 1] });
            infoFechas.style.display = 'none';
        } else {
            inicializarFlatpickr(); // sin restricciones hasta seleccionar semana
        }
    }

    tipoSelect.addEventListener("change", toggleCampos);
    toggleCampos(); // aplicar al cargar

    // ==========================================================
    // CARGAR FECHAS DISPONIBLES DESDE EVENTO PADRE
    // ==========================================================
    if (eventoPadre) {
        eventoPadre.addEventListener("change", function () {
            const idPadre = this.value;
            if (!idPadre || tipoSelect.value !== "diario") return;

            fetch(`/eventos/fechas-semana/${idPadre}`)
                .then(res => res.json())
                .then(({ inicio, fin, ocupados }) => {
                    inicializarFlatpickr({
                        minDate: inicio,
                        maxDate: fin,
                        disable: ocupados
                    });

                    infoFechas.innerHTML = `Fechas disponibles: <strong>${inicio}</strong> al <strong>${fin}</strong>`;
                    infoFechas.style.display = "block";
                });
        });
    }

    // ==========================================================
    // VALIDAR HORAS EN EVENTOS DIARIOS
    // ==========================================================
    form.addEventListener('submit', function (e) {
        if (tipoSelect.value === 'diario') {
            const horaInicio = form.querySelector('input[name="hora"]').value;
            const horaFin = form.querySelector('input[name="hora_termino"]').value;

            if (horaInicio && horaFin && horaFin <= horaInicio) {
                e.preventDefault();
                alert("La hora de término debe ser posterior a la hora de inicio.");
            }
        }
    });

    // ==========================================================
    // ABRIR Y CERRAR MODAL DE CATEGORÍA
    // ==========================================================
    const modal = document.getElementById('modalCategoria');

    document.getElementById('btnNuevaCategoria').addEventListener('click', () => {
        modal.classList.remove('hidden');
        modal.querySelector('form').reset();
        modal.querySelector('form').removeAttribute('action');
        modal.dataset.editing = 'false';
    });

    document.getElementById('btnCancelarCategoria').addEventListener('click', () => {
        modal.classList.add('hidden');
        modal.querySelector('form').reset();
        modal.dataset.editing = 'false';
    });

    // ==========================================================
    // CREAR O EDITAR CATEGORÍA (POST o PUT)
    // ==========================================================
    document.getElementById('formCategoria').addEventListener('submit', async function (e) {
        e.preventDefault();

        const form = e.target;
        const formData = new FormData(form);
        const isEditing = modal.dataset.editing === 'true';
        const method = isEditing ? 'PUT' : 'POST';
        const url = isEditing
            ? form.getAttribute('action')
            : window.categoriaStoreUrl;

        const response = await fetch(url, {
            method,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: formData
        });

        const data = await response.json();

        if (response.ok) {
            // Agregar o actualizar en el select
            const select = document.getElementById('categoria_id');
            if (isEditing) {
                const option = select.querySelector(`option[value="${data.id}"]`);
                if (option) option.textContent = data.nombre;
            } else {
                const option = document.createElement('option');
                option.value = data.id;
                option.textContent = data.nombre;
                select.appendChild(option);
                select.value = data.id;
            }

            // Agregar visualmente a la lista
            const listaCategorias = document.querySelector('.space-y-2');
            if (listaCategorias && data.id != 1) {
                const div = document.createElement('div');
                div.className = 'flex items-center justify-between bg-gray-100 rounded-lg px-4 py-2';
                div.innerHTML = `
                    <div class="flex items-center gap-2">
                        <div class="w-5 h-5 rounded-full" style="background-color: ${data.color}"></div>
                        <span class="text-sm font-medium">${data.nombre}</span>
                    </div>
                    <div class="flex gap-2">
                        <form action="/admin/categorias/${data.id}" method="POST" class="formEliminarCategoria">
                            <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').getAttribute('content')}">
                            <input type="hidden" name="_method" value="DELETE">
                            <button type="submit" class="text-red-600 text-sm hover:underline">Eliminar</button>
                        </form>
                    </div>
                `;
                listaCategorias.appendChild(div);
            }

            modal.classList.add('hidden');
            form.reset();
            modal.dataset.editing = 'false';

        } else {
            alert('Error al guardar la categoría');
        }
    });

    // ==========================================================
    // CONFIRMAR ANTES DE ELIMINAR CATEGORÍA
    // ==========================================================
    document.addEventListener('submit', function (e) {
        if (e.target.matches('form[action*="/admin/categorias/"]')) {
            const confirmed = confirm("⚠️ Al eliminar esta categoría, todos los eventos asociados serán reasignados a 'Sin categoría'. Ademas, se borrara todo lo que hayas esrito referente a este evento. ¿Deseas continuar?");
            if (!confirmed) {
                e.preventDefault();
            }
        }
    });

});
// ==========================================================