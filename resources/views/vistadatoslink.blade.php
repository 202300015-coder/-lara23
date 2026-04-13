<h1>Lista de peliculas de comedia</h1>

@if(empty($enlace))
    <p>No hay datos disponibles.</p>
@else
    @foreach($enlace as $en)
        <div style="border:1px solid #FEE; margin:7px; padding:7px;">
            <h3>{{ $en['title'] ?? 'Sin titulo' }}</h3>
            <p>{{ $en['year'] ?? 'N/D' }}</p>
        </div>
    @endforeach
@endif
