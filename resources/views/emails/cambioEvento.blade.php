<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Notificación de cambios en evento</title>
</head>
<body style="margin:0; padding:0; background-color:#f4f4f4; font-family:Arial, sans-serif; color:#333;">
  <table align="center" width="100%" cellpadding="0" cellspacing="0" style="max-width:600px; margin:40px auto; background-color:#ffffff; border-radius:8px; overflow:hidden; box-shadow:0 2px 8px rgba(0,0,0,0.1);">

    {{-- Cabecera --}}
    <tr>
      <td style="background-color:#328E6E; padding:20px; text-align:center;">
        <h2 style="color:white; margin:0;">Evento Actualizado</h2>
        <p style="color:white; margin:5px 0 0;">Universidad de Atacama</p>
      </td>
    </tr>

    {{-- Cuerpo --}}
    <tr>
      <td style="padding:30px;">
        <p>Hola <strong>{{ $nombre }}</strong>,</p>

        <p>Te informamos que el evento al que estás inscrito ha sido <strong>modificado recientemente</strong> 📌.</p>

        <p><strong>Detalles actualizados del evento:</strong></p>

        <table cellpadding="6" cellspacing="0" style="width:100%; font-size:15px; border-collapse:collapse; margin-bottom:20px;">
          <tr>
            <td style="font-weight:bold;">📅 Evento:</td>
            <td>{{ $evento->titulo }}</td>
          </tr>
          <tr>
            <td style="font-weight:bold;">📍 Lugar:</td>
            <td>{{ $evento->lugar }}</td>
          </tr>
          <tr>
            <td style="font-weight:bold;">🕒 Fecha:</td>
            <td>{{ \Carbon\Carbon::parse($evento->fecha)->format('d/m/Y') }}</td>
          </tr>
          @if($evento->hora)
          <tr>
            <td style="font-weight:bold;">⏰ Hora:</td>
            <td>{{ $evento->hora }} - {{ $evento->hora_termino }}</td>
          </tr>
          @endif
        </table>

        <p><strong>Resumen de cambios:</strong></p>
        <ul style="padding-left:18px; margin-bottom:20px;">
          @foreach ($cambios as $campo => $valores)
            <li style="margin-bottom:8px;">
              🔄 <strong>{{ ucfirst($campo) }}:</strong> 
              <em>{{ $valores['antes'] ?? 'N/A' }}</em> → <strong>{{ $valores['después'] ?? 'N/A' }}</strong>
            </li>
          @endforeach
        </ul>

        <p>Si tienes dudas o necesitas más información, no dudes en contactarnos.</p>

        <p>Saludos cordiales,<br>
      </td>
    </tr>

    {{-- Pie --}}
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
