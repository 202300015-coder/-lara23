<h1>Detalle de la pelicula</h1>

@php
    $pelicula = $pelicula ?? [];
    $imagenKey = null;
    $imagenUrl = null;

    foreach (['posterURL', 'posterUrl', 'poster', 'image', 'imagen', 'urlImagen', 'thumbnail'] as $key) {
        if (!empty($pelicula[$key]) && is_string($pelicula[$key])) {
            $imagenKey = $key;
            $imagenUrl = $pelicula[$key];
            break;
        }
    }

    if (!$imagenUrl) {
        foreach ($pelicula as $key => $value) {
            if (is_string($value) && preg_match('/^https?:\/\//i', $value) && preg_match('/\.(jpg|jpeg|png|webp|gif)(\?|$)/i', $value)) {
                $imagenKey = $key;
                $imagenUrl = $value;
                break;
            }
        }
    }

    $esCampoId = function ($key) {
        $lower = strtolower((string) $key);
        return $lower === 'id' || str_starts_with($lower, 'id') || str_ends_with($lower, 'id');
    };
@endphp

@if($imagenUrl)
    <img src="{{ $imagenUrl }}" alt="Imagen de pelicula" style="max-width:360px; width:100%; border-radius:8px; margin:0 0 12px 0;" />
@endif

<div style="border:1px solid #ddd; border-radius:8px; padding:12px; max-width:700px;">
    @foreach($pelicula as $clave => $valor)
        @if(!$esCampoId($clave) && $clave !== $imagenKey)
            <p style="margin:0 0 8px 0;">
                <strong>{{ ucwords(str_replace('_', ' ', preg_replace('/([a-z])([A-Z])/', '$1 $2', (string) $clave))) }}:</strong>
                {{ is_array($valor) || is_object($valor) ? json_encode($valor, JSON_UNESCAPED_UNICODE) : $valor }}
            </p>
        @endif
    @endforeach
</div>

<p style="margin-top:14px;">
    <a href="{{ url('/viewmio') }}" style="display:inline-block; padding:6px 12px; border:1px solid #999; background:#f5f5f5; border-radius:4px; color:#000; text-decoration:none;">
        Volver al listado
    </a>
</p>
