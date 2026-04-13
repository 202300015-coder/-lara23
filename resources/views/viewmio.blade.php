<h1>Mi API Personal</h1>

@if(!empty($mensaje))
    <p style="background:#fff3cd; border:1px solid #ffe69c; padding:10px;">{{ $mensaje }}</p>
@endif

@forelse(($enlace ?? []) as $en)
    <div style="border:1px solid #ddd; margin:10px; padding:10px; display:flex; gap:12px; align-items:flex-start;">
        @if(!empty($en['posterURL']))
            <img src="{{ $en['posterURL'] }}" alt="Poster" style="width:120px; height:170px; object-fit:cover; border:1px solid #ccc;">
        @endif
        <div>
            <h3 style="margin:0 0 8px 0;">{{ $en['title'] ?? 'Sin titulo' }}</h3>
            <p style="margin:0;">Ano: {{ $en['year'] ?? 'N/D' }}</p>
        </div>
    </div>
@empty
    <p>No hay datos disponibles desde la API.</p>
@endforelse