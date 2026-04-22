<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Productos</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; background: #f4f6f8; color: #1f2937; }
        .container { max-width: 1200px; margin: 28px auto; padding: 0 16px; }
        h1 { margin: 0 0 16px; }
        h2 { margin: 0 0 12px; font-size: 22px; }
        .message { background: #e8f7eb; color: #116530; border: 1px solid #b8e6c2; padding: 10px 12px; border-radius: 6px; margin-bottom: 14px; }
        .errors { background: #fdecec; color: #8a1c1c; border: 1px solid #f5c2c2; padding: 10px 12px; border-radius: 6px; margin-bottom: 14px; }
        .card { background: #fff; border: 1px solid #d8dee4; border-radius: 8px; padding: 16px; margin-bottom: 18px; }
        .form-grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 10px; }
        .field-full { grid-column: 1 / -1; }
        label { display: block; margin-bottom: 4px; font-weight: 600; }
        input, textarea, select { width: 100%; box-sizing: border-box; padding: 8px; border: 1px solid #c6ccd3; border-radius: 6px; }
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
        @media (max-width: 768px) {
            .form-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Productos</h1>

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
            <h2>Crear producto</h2>
            <form method="POST" action="{{ route('products.store') }}">
                @csrf
                <div class="form-grid">
                    <div>
                        <label for="name">Nombre</label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}" required>
                    </div>
                    <div>
                        <label for="price">Precio</label>
                        <input type="number" id="price" name="price" step="0.01" min="0" value="{{ old('price') }}" required>
                    </div>
                    <div>
                        <label for="category_id">Categoria</label>
                        <select id="category_id" name="category_id">
                            <option value="">Sin categoria</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" @selected(old('category_id') == $category->id)>{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="description">Descripcion corta</label>
                        <input type="text" id="description" name="description" value="{{ old('description') }}" required>
                    </div>
                    <div class="field-full">
                        <label for="descriptionLong">Descripcion larga</label>
                        <textarea id="descriptionLong" name="descriptionLong" rows="4" required>{{ old('descriptionLong') }}</textarea>
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
                        <th>Precio</th>
                        <th>Categoria</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                        <tr>
                            <td>{{ $product->id }}</td>
                            <td>{{ $product->name }}</td>
                            <td>{{ $product->description }}</td>
                            <td>{{ number_format($product->price, 2) }}</td>
                            <td>{{ $product->category?->name ?? 'Sin categoria' }}</td>
                            <td>
                                <div class="actions">
                                    <a class="link-edit" href="{{ route('products.edit', $product) }}">Editar</a>
                                    <form method="POST" action="{{ route('products.destroy', $product) }}" onsubmit="return confirm('¿Eliminar este producto?');">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn-delete" type="submit">Eliminar</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6">No hay productos registrados.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Productos</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; background: #f4f6f8; color: #1f2937; }
        .container { max-width: 1200px; margin: 28px auto; padding: 0 16px; }
        h1 { margin: 0 0 16px; }
        h2 { margin: 0 0 12px; font-size: 22px; }
        .message { background: #e8f7eb; color: #116530; border: 1px solid #b8e6c2; padding: 10px 12px; border-radius: 6px; margin-bottom: 14px; }
        .errors { background: #fdecec; color: #8a1c1c; border: 1px solid #f5c2c2; padding: 10px 12px; border-radius: 6px; margin-bottom: 14px; }
        .card { background: #fff; border: 1px solid #d8dee4; border-radius: 8px; padding: 16px; margin-bottom: 18px; }
        .form-grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 10px; }
        .form-grid .full { grid-column: 1 / -1; }
        label { display: block; margin-bottom: 4px; font-weight: 600; }
        input, textarea, select { width: 100%; box-sizing: border-box; padding: 8px; border: 1px solid #c6ccd3; border-radius: 6px; }
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
        @media (max-width: 768px) {
            .form-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Productos</h1>

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
            <h2>Crear producto</h2>
            <form method="POST" action="{{ route('products.store') }}">
                @csrf
                <div class="form-grid">
                    <div>
                        <label for="name">Nombre</label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}" required>
                    </div>

                    <div>
                        <label for="price">Precio</label>
                        <input type="number" id="price" name="price" value="{{ old('price') }}" min="0" step="0.01" required>
                    </div>

                    <div>
                        <label for="description">Descripcion corta</label>
                        <input type="text" id="description" name="description" value="{{ old('description') }}" required>
                    </div>

                    <div>
                        <label for="category_id">Categoria</label>
                        <select id="category_id" name="category_id">
                            <option value="">Sin categoria</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" @selected(old('category_id') == $category->id)>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="full">
                        <label for="descriptionLong">Descripcion larga</label>
                        <textarea id="descriptionLong" name="descriptionLong" rows="4" required>{{ old('descriptionLong') }}</textarea>
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
                        <th>Precio</th>
                        <th>Descripcion</th>
                        <th>Categoria</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $productItem)
                        <tr>
                            <td>{{ $productItem->id }}</td>
                            <td>{{ $productItem->name }}</td>
                            <td>{{ number_format($productItem->price, 2) }}</td>
                            <td>{{ $productItem->description }}</td>
                            <td>{{ $productItem->category?->name ?? 'Sin categoria' }}</td>
                            <td>
                                <div class="actions">
                                    <a class="link-edit" href="{{ route('products.edit', $productItem) }}">Editar</a>
                                    <form method="POST" action="{{ route('products.destroy', $productItem) }}" onsubmit="return confirm('¿Eliminar este producto?');">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn-delete" type="submit">Eliminar</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6">No hay productos registrados.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
