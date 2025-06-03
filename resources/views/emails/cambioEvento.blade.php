<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        ul { padding-left: 18px; }
        li { margin-bottom: 8px; }
    </style>
</head>
<body>
<p>Hola {{ $nombre }},</p>

<p>Te informamos que el evento al que estás inscrito ha sido <strong>modificado recientemente</strong>.</p>

<p><strong>Detalles actualizados del evento:</strong></p>
<ul>
    <li>📅 <strong>Evento:</strong> {{ $evento->titulo }}</li>
    <li>📍 <strong>Lugar:</strong> {{ $evento->lugar }}</li>
    <li>🕒 <strong>Fecha:</strong> {{ \Carbon\Carbon::parse($evento->fecha)->format('d/m/Y') }}</li>
    @if($evento->hora)
    <li>🕑 <strong>Hora:</strong> {{ $evento->hora }} - {{ $evento->hora_termino }}</li>
    @endif
</ul>

<p><strong>Resumen de cambios:</strong></p>
<ul>
    @foreach ($cambios as $campo => $valores)
        <li>
            🔄 <strong>{{ ucfirst($campo) }}:</strong>
            <em>{{ $valores['antes'] ?? 'N/A' }}</em> → <strong>{{ $valores['después'] ?? 'N/A' }}</strong>
        </li>
    @endforeach
</ul>

<p>Si tienes dudas o necesitas más información, no dudes en contactarnos.</p>

<p>Saludos cordiales,<br>
<strong>Sistema de Registro de Asistencia</strong><br>
Universidad de Atacama<br>
<small>[correo@uda.cl] | [sitio-web.cl]</small></p>
</body>
</html>
