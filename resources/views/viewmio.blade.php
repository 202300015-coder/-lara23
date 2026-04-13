<h1>Mi API Personal</h1>
<p>Fuente: {{ $apiUrl ?? 'No configurada' }}</p>

@if(!empty($mensaje))
    <p style="background:#fff3cd; border:1px solid #ffe69c; padding:10px;">{{ $mensaje }}</p>
@endif

@forelse(($enlace ?? []) as $en)
    <div style="border:1px solid #ddd; margin:10px; padding:10px;">
        <h3>{{ $en['title'] ?? 'Sin titulo' }}</h3>
    </div>
@empty
    <p>No hay datos disponibles desde la API.</p>
@endforelse