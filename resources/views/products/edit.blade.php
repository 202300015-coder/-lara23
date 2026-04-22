<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar producto</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; background: #f4f6f8; color: #1f2937; }
        .container { max-width: 900px; margin: 28px auto; padding: 0 16px; }
        .card { background: #fff; border: 1px solid #d8dee4; border-radius: 8px; padding: 16px; }
        h1 { margin: 0 0 14px; }
        .form-grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 10px; }
        .field-full { grid-column: 1 / -1; }
        label { display: block; margin-bottom: 4px; font-weight: 600; }
        input, textarea, select { width: 100%; box-sizing: border-box; padding: 8px; border: 1px solid #c6ccd3; border-radius: 6px; }
        .errors { background: #fdecec; color: #8a1c1c; border: 1px solid #f5c2c2; padding: 10px 12px; border-radius: 6px; margin-bottom: 14px; }
        .actions { display: flex; gap: 8px; margin-top: 10px; }
        .btn { padding: 8px 12px; border-radius: 6px; border: 1px solid #1f2937; background: #1f2937; color: #fff; cursor: pointer; }
        .btn:hover { background: #111827; }
        .btn-link { padding: 8px 12px; border-radius: 6px; border: 1px solid #9ca3af; background: #fff; color: #111827; text-decoration: none; }
        @media (max-width: 768px) {
            .form-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <h1>Editar producto</h1>

            @if(isset($errors) && $errors->any())
                <div class="errors">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('products.update', $product) }}">
                @csrf
                @method('PUT')

                <div class="form-grid">
                    <div>
                        <label for="name">Nombre</label>
                        <input type="text" id="name" name="name" value="{{ old('name', $product->name) }}" required>
                    </div>
                    <div>
                        <label for="price">Precio</label>
                        <input type="number" id="price" name="price" step="0.01" min="0" value="{{ old('price', $product->price) }}" required>
                    </div>
                    <div>
                        <label for="category_id">Categoria</label>
                        <select id="category_id" name="category_id">
                            <option value="">Sin categoria</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" @selected(old('category_id', $product->category_id) == $category->id)>{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="description">Descripcion corta</label>
                        <input type="text" id="description" name="description" value="{{ old('description', $product->description) }}" required>
                    </div>
                    <div class="field-full">
                        <label for="descriptionLong">Descripcion larga</label>
                        <textarea id="descriptionLong" name="descriptionLong" rows="4" required>{{ old('descriptionLong', $product->descriptionLong) }}</textarea>
                    </div>
                </div>

                <div class="actions">
                    <button class="btn" type="submit">Actualizar</button>
                    <a class="btn-link" href="{{ route('products.index') }}">Volver</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
