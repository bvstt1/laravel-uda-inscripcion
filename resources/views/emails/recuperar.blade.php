<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Recuperación de Contraseña</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f4f4f4; margin: 0; padding: 0;">
  <table align="center" width="100%" cellpadding="0" cellspacing="0" style="max-width: 600px; background-color: white; margin-top: 40px; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 10px rgba(0,0,0,0.1);">
    <tr>
      <td style="background-color: #328E6E; padding: 20px; text-align: center; color: white;">
        <h2>Plataforma de Eventos UDA</h2>
      </td>
    </tr>
    <tr>
      <td style="padding: 30px;">
        <p>Hola,</p>
        <p>Hemos recibido una solicitud para restablecer tu contraseña. Si no hiciste esta solicitud, puedes ignorar este mensaje.</p>
        <p style="text-align: center; margin: 30px 0;">
          <a href="{{ $link }}" style="background-color: #328E6E; color: white; padding: 12px 20px; text-decoration: none; border-radius: 6px; display: inline-block;">
            Restablecer contraseña
          </a>
        </p>
        <p>Este enlace expirará en 60 minutos.</p>
        <p>Gracias,<br>Equipo de la Universidad de Atacama</p>
      </td>
    </tr>
    <tr>
      <td style="background-color: #f0f0f0; padding: 15px; text-align: center; font-size: 12px; color: #777;">
        © {{ date('Y') }} Universidad de Atacama. Todos los derechos reservados.
      </td>
    </tr>
  </table>
</body>
</html>
