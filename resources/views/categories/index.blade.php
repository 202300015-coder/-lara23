<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Categorias</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 24px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        .actions a, .actions button { padding: 4px 8px; border: 1px solid #999; background: #f3f3f3; text-decoration: none; color: #000; cursor: pointer; }
        .actions { display: flex; gap: 6px; }
    </style>
</head>
<body>
    <h1>Categorias</h1>

    @if(session('mensaje'))
        <p>{{ session('mensaje') }}</p>
    @endif

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Descripcion</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse($categories as $categoryItem)
                <tr>
                    <td>{{ $categoryItem->id }}</td>
                    <td>{{ $categoryItem->name }}</td>
                    <td>{{ $categoryItem->description }}</td>
                    <td>
                        <div class="actions">
                            <a href="{{ route('categories.edit', $categoryItem) }}">Editar</a>
                            <form method="POST" action="{{ route('categories.destroy', $categoryItem) }}" onsubmit="return confirm('¿Eliminar esta categoria?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit">Eliminar</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4">No hay categorias registradas.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>