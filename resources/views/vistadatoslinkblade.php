<h1>lista de datos de la </h1>
@foreach($enlace as $en)
<div>
<h3>{{$en['title']}}</h3>
<p>{{$en['year']}}</p>
</div>
@endforeach
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>lista de datos de la</title>
    <style>
        body {
            margin: 0;
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
            background: #f5f7fb;
            color: #1f2937;
        }
        .container {
            max-width: 1100px;
            margin: 30px auto;
            padding: 0 16px;
        }
        h1 {
            margin: 0 0 20px;
            font-size: 2rem;
        }
        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
            gap: 16px;
        }
        .card {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }
        .poster {
            width: 100%;
            height: 320px;
            object-fit: cover;
            background: #eef2f7;
        }
        .content {
            padding: 12px;
        }
        .title {
            margin: 0 0 8px;
            font-size: 1rem;
            line-height: 1.3;
        }
        .year {
            margin: 0;
            color: #6b7280;
            font-size: 0.9rem;
        }
        .empty {
            background: #fff3cd;
            border: 1px solid #ffe69c;
            color: #664d03;
            padding: 12px;
            border-radius: 8px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Peliculas de comedia</h1>

        @if(empty($enlace))
            <p class="empty">No se pudieron cargar datos de la API en este momento.</p>
        @else
            <div class="grid">
                @foreach($enlace as $en)
                    <article class="card">
                        <img class="poster" src="{{ $en['posterURL'] ?? '' }}" alt="Poster de {{ $en['title'] ?? 'pelicula' }}">
                        <div class="content">
                            <h3 class="title">{{ $en['title'] ?? 'Sin titulo' }}</h3>
                            <p class="year">Ano: {{ $en['year'] ?? 'N/D' }}</p>
                        </div>
                    </article>
                @endforeach
            </div>
        @endif
    </div>
</body>
</html>
