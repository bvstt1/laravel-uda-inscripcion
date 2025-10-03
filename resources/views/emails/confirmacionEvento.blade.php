<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Confirmación de inscripción</title>
</head>
<body style="margin: 0; padding: 0; background-color: #f4f4f4; font-family: Arial, sans-serif; color: #333;">
  <table align="center" width="100%" cellpadding="0" cellspacing="0" style="max-width: 600px; margin: 40px auto; background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
    
    {{-- Cabecera --}}
    <tr>
      <td style="background-color: #328E6E; padding: 20px; text-align: center;">
        <h2 style="color: white; margin: 0;">Confirmación de Inscripción</h2>
        <p style="color: white; margin: 5px 0 0;">Universidad de Atacama</p>
      </td>
    </tr>

    {{-- Cuerpo --}}
    <tr>
      <td style="padding: 30px;">
        <p>Hola <strong>{{ $nombre }}</strong>,</p>

        <p>¡Tu inscripción ha sido confirmada exitosamente! 🎉</p>

        <p>Gracias por registrarte en el siguiente evento:</p>

        <table cellpadding="6" cellspacing="0" style="width: 100%; font-size: 15px;">
          <tr>
            <td><strong>📅 Evento:</strong></td>
            <td>{{ $evento }}</td>
          </tr>
          <tr>
            <td><strong>📍 Lugar:</strong></td>
            <td>{{ $lugar }}</td>
          </tr>
          <tr>
            <td><strong>🕒 Fecha y hora:</strong></td>
            <td>{{ $fechaHora }}</td>
          </tr>
        </table>

        <p style="margin-top: 25px;"><strong>🔐 Código QR de acceso:</strong></p>

        <div style="text-align: center; margin: 20px 0;">
          <img src="{{ $message->embed($qrPath) }}" alt="Código QR" width="200" height="200"/>
        </div>

        <p>📌 Recuerda presentar tu <strong>código QR o RUT</strong> al ingresar al evento para validar tu asistencia.</p>

        <p>Si tienes dudas o necesitas ayuda, no dudes en contactarnos.</p>

        <p>¡Nos vemos pronto!</p>
      </td>
    </tr>

    {{-- Pie --}}
    <tr>
      <td style="background-color: #f0f0f0; padding: 20px; text-align: center; font-size: 13px; color: #666;">
        <strong>Portal de Eventos del HUB</strong><br>
        Universidad de Atacama<br>
        <a href="mailto:hub.uda@uda.cl" style="color: #328E6E; text-decoration: none;">correo@uda.cl</a> | 
        <a href="https://sitio-web.cl" target="_blank" style="color: #328E6E; text-decoration: none;">uda.cl</a>
      </td>
    </tr>
  </table>
</body>
</html>
