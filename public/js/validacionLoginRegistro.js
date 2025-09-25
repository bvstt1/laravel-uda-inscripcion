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
// VALIDAR NOMBRE Y APELLIDO (solo letras y espacios simples)
// ==========================================================
function validarNombreApellido(input) {
  let valor = input.value;

  // Permitir letras (mayúsculas/minúsculas), espacios, guiones y acento suelto
  valor = valor.replace(/[^A-Za-zÁÉÍÓÚáéíóúÑñ ´-]/g, '');

  // Evitar espacios dobles
  valor = valor.replace(/\s{2,}/g, ' ');

  // Evitar acentos dobles consecutivos
  valor = valor.replace(/´{2,}/g, '´');

  // Quitar espacios al inicio y al final
  valor = valor.trim();

  input.value = valor;

  // Mensaje de validación
  const mensaje = document.getElementById(`mensaje-${input.name}`);
  if (mensaje) {
    if (valor.length < 2) {
      mensaje.textContent = 'Debe tener al menos 2 caracteres válidos.';
      mensaje.classList.remove('hidden');
    } else {
      mensaje.textContent = '';
      mensaje.classList.add('hidden');
    }
  }
}
// ==========================================================
document.addEventListener('DOMContentLoaded', () => {
  const contrasenaInput = document.getElementById('contrasena');
  const toggleBtn = document.getElementById('toggleContrasena');
  const iconoOjo = document.getElementById('iconoOjo');

  toggleBtn.addEventListener('click', () => {
    if (contrasenaInput.type === 'password') {
      contrasenaInput.type = 'text';
      // Cambiar SVG a ojo cerrado
      iconoOjo.innerHTML = `
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
            d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.269-2.943-9.543-7a10.05 10.05 0 012.745-4.223M9.88 9.88a3 3 0 104.24 4.24"/>
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
            d="M3 3l18 18"/>
    `;
    } else {
      contrasenaInput.type = 'password';
      // Ojo abierto
      iconoOjo.innerHTML = `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>`;
    }
  });
});

