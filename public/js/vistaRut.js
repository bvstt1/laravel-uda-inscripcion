function validarRutSoloNumeros(input) {
  let valor = input.value.toUpperCase(); // Convertir a mayúscula
  const mensaje = document.getElementById('mensaje-rut');

  // Eliminar todo lo que no sea número o K
  valor = valor.replace(/[^0-9K]/g, '');

  // Solo permitir una 'K' y solo al final
  const partes = valor.match(/^(\d{0,8})(K?)$/);
  if (partes) {
    input.value = partes[1] + partes[2];
    mensaje.textContent = '';
  }
}


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

function validarContrasena(input) {
  const mensaje = document.getElementById('mensaje-contrasena');
  const regex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/;

  if (input.value === '') {
    // Ocultar si está vacío
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


document.addEventListener('DOMContentLoaded', function () {
  const errorRut = document.getElementById('error-rut');
  if (errorRut) {
    setTimeout(() => {
      errorRut.style.opacity = 0;
    }, 3000);
  }

  const alertaRut = document.getElementById('alerta-rut');
  if (alertaRut) {
    setTimeout(() => {
      alertaRut.style.opacity = 0;
      setTimeout(() => alertaRut.style.display = 'none', 300); // Espera el fade out
    }, 3000);
  }

  const alertaCorreo = document.getElementById('alerta-correo');
  if (alertaCorreo) {
    setTimeout(() => {
      alertaCorreo.style.opacity = 0;
      setTimeout(() => alertaCorreo.style.display = 'none', 300);
    }, 3000);
  }
  
   // Agregar validaciones a campos si están presentes
  const rut = document.getElementById('rut');
  if (rut) rut.addEventListener('input', () => validarRutSoloNumeros(rut));

  const correo = document.querySelector('input[name="correo"]');
  if (correo) correo.addEventListener('input', () => validarCorreo(correo));

  const contrasena = document.getElementById('contrasena');
  if (contrasena) contrasena.addEventListener('input', () => validarContrasena(contrasena));
});
