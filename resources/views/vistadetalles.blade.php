<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
  
</head>
<body>
    <h1>Detalle de la pelicula {{ $pelicula['id'] ?? '' }}</h1>

    @php
        $pelicula = $pelicula ?? [];
    @endphp

    @foreach($pelicula as $clave => $valor)
        <p>
            <strong>{{ $clave }}:</strong> {{ is_array($valor) || is_object($valor) ? json_encode($valor) : $valor }}
        </p>
    @endforeach

    <p style="margin-top:14px;">
        <a href="{{ url('/viewmio') }}" style="display:inline-block; padding:6px 12px; border:1px solid #999; background:#f5f5f5; border-radius:4px; color:#000; text-decoration:none;">
            Volver al listado
        </a>
    </p>
</body>
</html>
