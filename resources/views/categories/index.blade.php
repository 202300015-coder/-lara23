<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Categorias</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; background: #f4f6f8; color: #1f2937; }
        .container { max-width: 1100px; margin: 28px auto; padding: 0 16px; }
        h1 { margin: 0 0 16px; }
        h2 { margin: 0 0 12px; font-size: 22px; }
        .message { background: #e8f7eb; color: #116530; border: 1px solid #b8e6c2; padding: 10px 12px; border-radius: 6px; margin-bottom: 14px; }
        .errors { background: #fdecec; color: #8a1c1c; border: 1px solid #f5c2c2; padding: 10px 12px; border-radius: 6px; margin-bottom: 14px; }
        .card { background: #fff; border: 1px solid #d8dee4; border-radius: 8px; padding: 16px; margin-bottom: 18px; }
        .form-grid { display: grid; grid-template-columns: 1fr; gap: 10px; }
        label { display: block; margin-bottom: 4px; font-weight: 600; }
        input, textarea { width: 100%; box-sizing: border-box; padding: 8px; border: 1px solid #c6ccd3; border-radius: 6px; }
        .btn { padding: 8px 12px; border-radius: 6px; border: 1px solid #1f2937; background: #1f2937; color: #fff; cursor: pointer; }
        .btn:hover { background: #111827; }
        .table-wrap { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; background: #fff; }
        th, td { border: 1px solid #e3e8ee; padding: 10px; text-align: left; vertical-align: top; }
        thead th { background: #f8fafc; }
        .actions { display: flex; align-items: center; gap: 8px; }
        .link-edit { color: #1d4ed8; text-decoration: none; font-weight: 600; }
        .link-edit:hover { text-decoration: underline; }
        .btn-delete { padding: 6px 10px; border: 1px solid #a11d33; background: #fff; color: #a11d33; border-radius: 6px; cursor: pointer; }
        .btn-delete:hover { background: #fff1f3; }
    </style>
</head>
<body>
    <div class="container">
    <h1>Categorias</h1>

    @if(session('mensaje'))
        <div class="message">{{ session('mensaje') }}</div>
    @endif

    @if(isset($errors) && $errors->any())
        <div class="errors">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card">
    <h2>Crear categoria</h2>
    <form method="POST" action="{{ route('categories.store') }}">
        @csrf
        <div class="form-grid">
            <div>
                <label for="name">Nombre</label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" required>
            </div>
            <div>
                <label for="description">Descripcion</label>
                <textarea id="description" name="description" rows="3">{{ old('description') }}</textarea>
            </div>
        </div>
        <button type="submit" class="btn">Guardar</button>
    </form>
    </div>

    <div class="card table-wrap">
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
                            <a class="link-edit" href="{{ route('categories.edit', $categoryItem) }}">Editar</a>
                            <form method="POST" action="{{ route('categories.destroy', $categoryItem) }}" onsubmit="return confirm('¿Eliminar esta categoria?');">
                                @csrf
                                @method('DELETE')
                                <button class="btn-delete" type="submit">Eliminar</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="4">No hay categorias registradas.</td></tr>
            @endforelse
        </tbody>
    </table>
    </div>
    </div>
</body>
</html>