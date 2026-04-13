<h1>Mi API Personal</h1>

@if(!empty($mensaje))
    <p style="background:#fff3cd; border:1px solid #ffe69c; padding:10px;">{{ $mensaje }}</p>
@endif

@forelse(($enlace ?? []) as $en)
    <div style="border:1px solid #ddd; margin:10px; padding:10px;">
        <h3 style="margin:0 0 8px 0;">{{ $en['title'] ?? 'Sin titulo' }}</h3>
        <button type="button" style="padding:6px 12px; border:1px solid #999; background:#f5f5f5; border-radius:4px; cursor:default;">
            Ver detalle
        </button>
    </div>
@empty
    <p>No hay datos disponibles desde la API.</p>
@endforelse