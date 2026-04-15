<h1>Mi API Personal</h1>

@if(!empty($mensaje))
    <p style="background:#fff3cd; border:1px solid #ffe69c; padding:10px;">{{ $mensaje }}</p>
@endif

@forelse(($enlace ?? []) as $en)
    <div style="border:1px solid #ddd; margin:10px; padding:10px;">
        <h3 style="margin:0 0 8px 0;">{{ $en['title'] ?? 'Sin titulo' }}</h3>
        @if(isset($en['id']))
            <a href="{{ route('tj.detalle', $en['id']) }}" style="display:inline-block; padding:6px 12px; border:1px solid #999; background:#f5f5f5; border-radius:4px; color:#000; text-decoration:none;">
                Ver detalle
            </a>
        @else
            <span style="display:inline-block; padding:6px 12px; border:1px solid #ccc; background:#f5f5f5; border-radius:4px; color:#777;">
                Sin id para detalle
            </span>
        @endif
    </div>
@empty
    <p>No hay datos disponibles desde la API.</p>
@endforelse