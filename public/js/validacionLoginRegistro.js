// ==========================================================
// VALIDAR RUT (Solo números y letra K final)
// ==========================================================
function validarRutSoloNumeros(input) {
  let valor = input.value.toUpperCase();
  const mensaje = document.getElementById('mensaje-rut');
  const alerta = document.getElementById('alerta-rut');

  // Eliminar todo lo que no sea número o K
  valor = valor.replace(/[^0-9K]/g, '');

  // Permitir solo una 'K' y solo al final
  const partes = valor.match(/^(\d{0,8})(K?)$/);
  if (partes) {
    input.value = partes[1] + partes[2];
  } else {
    if (alerta) {
      alerta.textContent = 'Formato de RUT inválido.';
      alerta.classList.remove('hidden');
      alerta.style.opacity = 1;

      setTimeout(() => {
        alerta.style.opacity = 0;
        setTimeout(() => alerta.classList.add('hidden'), 300);
      }, 3000);
    }
  }

  // Mensaje orientador si está vacío
  if (input.value === '') {
    mensaje.textContent = 'Escribe tu rut sin puntos ni guión';
    mensaje.classList.remove('hidden');
  } else {
    mensaje.textContent = '';
    mensaje.classList.add('hidden');
  }
}

// ==========================================================
// VALIDAR CORREO (Solo @alumnos.uda.cl si es estudiante)
// ==========================================================
function validarCorreo(input) {
  const mensaje = document.getElementById('mensaje-correo');
  if (!mensaje) return;

  const esEstudiante = window.location.pathname.includes('registroEstudiante');
  const correo = input.value.trim();

  if (correo === '') {
    mensaje.textContent = '';
    mensaje.classList.add('hidden');
    return;
  }

  if (esEstudiante && !correo.endsWith('@alumnos.uda.cl')) {
    mensaje.textContent = 'El correo debe terminar en @alumnos.uda.cl';
    mensaje.classList.remove('hidden');
  } else {
    mensaje.textContent = '';
    mensaje.classList.add('hidden');
  }
}

// ==========================================================
// VALIDAR CONTRASEÑA (mínimo 8 caracteres, reglas complejas)
// ==========================================================
function validarContrasena(input) {
  const mensaje = document.getElementById('mensaje-contrasena');
  const regex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/;

  if (input.value === '') {
    mensaje.classList.add('hidden');
    mensaje.textContent = '';
    return;
  }

  if (!regex.test(input.value)) {
    mensaje.textContent = 'Tu contraseña debe tener al menos 8 caracteres, una mayúscula, una minúscula, un número y un símbolo.';
    mensaje.classList.remove('hidden');
  } else {
    mensaje.classList.add('hidden');
    mensaje.textContent = '';
  }
}

// ==========================================================
// VALIDAR CONFIRMACIÓN DE CONTRASEÑA
// ==========================================================
function validarConfirmacionContrasena() {
  const contrasena = document.getElementById('contrasena');
  const confirmacion = document.getElementById('contrasena_confirmation');
  const mensaje = document.getElementById('mensaje-confirmacion-js');

  if (!mensaje || !contrasena || !confirmacion) return;

  if (confirmacion.value === '') {
    mensaje.classList.add('hidden');
    mensaje.textContent = '';
    return;
  }

  if (contrasena.value !== confirmacion.value) {
    mensaje.textContent = 'Las contraseñas no coinciden.';
    mensaje.classList.remove('hidden');
  } else {
    mensaje.textContent = '';
    mensaje.classList.add('hidden');
  }
}

// ==========================================================
// EVENTOS AL CARGAR DOM
// ==========================================================
document.addEventListener('DOMContentLoaded', function () {
  // Ocultar automáticamente mensajes de error después de 3 segundos
  const errores = ['error-rut', 'alerta-rut', 'alerta-correo', 'mensaje-error-login'];
  errores.forEach(id => {
    const el = document.getElementById(id);
    if (el) {
      setTimeout(() => {
        el.style.opacity = 0;
        setTimeout(() => el.style.display = 'none', 300);
      }, 3000);
    }
  });

  // Asignar validaciones en tiempo real si existen los campos
  const rut = document.getElementById('rut');
  if (rut) rut.addEventListener('input', () => validarRutSoloNumeros(rut));

  const correo = document.querySelector('input[name="correo"]');
  if (correo) correo.addEventListener('input', () => validarCorreo(correo));

  const contrasena = document.getElementById('contrasena');
  if (contrasena) contrasena.addEventListener('input', () => validarContrasena(contrasena));

  const confirmar = document.getElementById('contrasena_confirmation');
  if (confirmar) confirmar.addEventListener('input', validarConfirmacionContrasena);
});
// ==========================================================