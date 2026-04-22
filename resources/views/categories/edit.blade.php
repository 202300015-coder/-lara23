<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar categoria</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; background: #f4f6f8; color: #1f2937; }
        .container { max-width: 760px; margin: 28px auto; padding: 0 16px; }
        .card { background: #fff; border: 1px solid #d8dee4; border-radius: 8px; padding: 16px; }
        h1 { margin: 0 0 14px; }
        label { display: block; margin-bottom: 4px; font-weight: 600; }
        input, textarea { width: 100%; box-sizing: border-box; padding: 8px; border: 1px solid #c6ccd3; border-radius: 6px; }
        .field { margin-bottom: 10px; }
        .errors { background: #fdecec; color: #8a1c1c; border: 1px solid #f5c2c2; padding: 10px 12px; border-radius: 6px; margin-bottom: 14px; }
        .actions { display: flex; gap: 8px; margin-top: 10px; }
        .btn { padding: 8px 12px; border-radius: 6px; border: 1px solid #1f2937; background: #1f2937; color: #fff; cursor: pointer; }
        .btn:hover { background: #111827; }
        .btn-link { padding: 8px 12px; border-radius: 6px; border: 1px solid #9ca3af; background: #fff; color: #111827; text-decoration: none; }
    </style>
</head>
<body>
    <div class="container">
    <div class="card">
    <h1>Editar categoria</h1>

    @if(isset($errors) && $errors->any())
        <div class="errors">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('categories.update', $category) }}">
        @csrf
        @method('PUT')

        <div class="field">
            <label for="name">Nombre</label>
            <input type="text" id="name" name="name" value="{{ old('name', $category->name) }}" required>
        </div>

        <div class="field">
            <label for="description">Descripcion</label>
            <textarea id="description" name="description" rows="4">{{ old('description', $category->description) }}</textarea>
        </div>

        <div class="actions">
            <button class="btn" type="submit">Actualizar</button>
            <a class="btn-link" href="{{ route('categories.index') }}">Volver</a>
        </div>
    </form>
    </div>
    </div>
</body>
</html>
