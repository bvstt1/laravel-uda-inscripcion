<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"></head>
<body>
<p>Hola {{ $nombre }},</p>

<p>¡Tu inscripción ha sido confirmada exitosamente! 🎉<br>
Gracias por registrarte en el siguiente evento:</p>

<ul>
  <li>📅 <strong>Evento:</strong> {{ $evento }}</li>
  <li>📍 <strong>Lugar:</strong> {{ $lugar }}</li>
  <li>🕒 <strong>Fecha y hora:</strong> {{ $fechaHora }}</li>
</ul>

<p>🔐 <strong>Código QR de acceso:</strong></p>
<img src="{{ $qrPath }}" alt="Código QR" style="width: 200px;" />

<p>Te recordamos que deberás presentar tu <strong>código QR o RUT</strong> al momento de ingresar para validar tu asistencia.</p>

<p>Si tienes dudas o necesitas asistencia, no dudes en contactarnos.</p>

<p>¡Nos vemos pronto!</p>

<p><strong>Sistema de Registro de Asistencia</strong><br>
Universidad de Atacama<br>
<small>[correo@uda.cl] | [sitio-web.cl]</small></p>
</body>
</html>
