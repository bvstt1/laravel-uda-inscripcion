document.addEventListener("DOMContentLoaded", function () {
    // ========================
    // Elementos clave
    // ========================
    const tipoSelect = document.getElementById("tipo");
    const eventoPadre = document.querySelector('select[name="id_evento_padre"]');
    const fechaInput = document.getElementById("fecha");
    const infoFechas = document.getElementById("info-fechas-semana");
    const semanaRelacionada = document.getElementById('semanaRelacionada');
    const horariosDiarios = document.getElementById('horariosDiarios');
    const form = document.querySelector('form');

    let flatpickrInstance;

    // ========================
    // Inicializa Flatpickr
    // ========================
    function inicializarFlatpickr(config = {}) {
        if (flatpickrInstance) flatpickrInstance.destroy();
        flatpickrInstance = flatpickr(fechaInput, Object.assign({ dateFormat: "Y-m-d" }, config));
    }

    // ========================
    // Lógica según tipo
    // ========================
    function toggleCampos() {
        const esSemanal = tipoSelect.value === 'semanal';

        // Mostrar u ocultar secciones
        semanaRelacionada.style.display = esSemanal ? 'none' : 'block';
        horariosDiarios.style.display = esSemanal ? 'none' : 'block';

        if (esSemanal) {
            // Limpiar horarios
            horariosDiarios.querySelectorAll('input').forEach(input => input.value = '');

            // Flatpickr: solo lunes
            inicializarFlatpickr({
                enable: [date => date.getDay() === 1]
            });

            // Ocultar info de semana
            infoFechas.style.display = 'none';

        } else {
            // Flatpickr básico hasta que elija una semana
            inicializarFlatpickr();
        }
    }

    tipoSelect.addEventListener("change", toggleCampos);
    toggleCampos(); // ejecutar al cargar

    // ========================
    // Cargar fechas válidas desde evento padre
    // ========================
    if (eventoPadre) {
        eventoPadre.addEventListener("change", function () {
            const idPadre = this.value;
            if (!idPadre || tipoSelect.value !== "diario") return;

            fetch(`/eventos/fechas-semana/${idPadre}`)
                .then(res => res.json())
                .then(data => {
                    const { inicio, fin, ocupados } = data;

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

    // ========================
    // Validación de horas
    // ========================
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
});
