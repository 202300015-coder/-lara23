# Explicacion del proyecto Render + Laravel

## Objetivo
El objetivo del trabajo fue dividir la solucion en dos partes:

1. Un **hosting** en Render que expone la informacion en formato JSON.
2. Un **proyecto local en Laravel** que consume ese hosting y muestra los datos en una vista.

Con eso se cumple la idea de tener una API en linea y una pagina local que lee esa API.

---

## 1. Que se hizo en Render

En Render se despliega el mismo proyecto Laravel, pero se usa una ruta que responde JSON plano.

La ruta principal del hosting queda apuntando a la funcion que devuelve el JSON de peliculas de comedia.

### Ruta principal del hosting
```php
Route::get('/', [ctrlDatos::class, 'ApiComedyHosted']);
```

### Ruta del JSON hospedado
```php
Route::get('/api/comedy-hosted', [ctrlDatos::class, 'ApiComedyHosted']);
```

### Funcion que devuelve JSON
```php
public function ApiComedyHosted()
{
    $response = Http::acceptJson()
        ->timeout(20)
        ->get('https://api.sampleapis.com/movies/comedy');

    if (!$response->successful()) {
        return response()->json([
            'message' => 'No se pudo obtener la fuente externa.',
        ], 502);
    }

    $data = $response->json();

    if (!is_array($data)) {
        $decoded = json_decode($response->body(), true);
        $data = is_array($decoded) ? $decoded : [];
    }

    return response()->json($data);
}
```

### Explicacion teorica
- `Http::acceptJson()` hace la peticion como cliente HTTP de Laravel.
- `timeout(20)` evita que la peticion se quede colgada demasiado tiempo.
- `get()` consulta la API externa de peliculas.
- Si falla, se responde con JSON de error y codigo 502.
- Si responde bien, se devuelve el arreglo JSON directamente.

Esto permite que el hosting funcione como una API publica.

---

## 2. Que se hizo en local

En el proyecto local se creo una vista que consume el hosting y muestra la informacion.

La vista local usa la misma logica del controlador, pero ya no depende de la API externa directa, sino del hosting de Render.

### Ruta local
```php
Route::get('/viewmio', [ctrlDatos::class, 'AccesoDatosViewMio']);
```

Tambien se dejo alias para la misma vista:

```php
Route::get('/view-mio', [ctrlDatos::class, 'AccesoDatosViewMio']);
```

### Funcion que consume el hosting
```php
public function AccesoDatosViewMio()
{
    $hostUrl = request()->getSchemeAndHttpHost();
    $defaultApiUrl = rtrim($hostUrl, '/') . '/api/comedy-hosted';
    $apiUrl = env('VIEW_MIO_API_URL', $defaultApiUrl);
    $mensaje = null;
    $fuenteMostrada = $apiUrl;
    $data = [];

    $partes = parse_url($apiUrl);
    if (!empty($partes['scheme']) && !empty($partes['host'])) {
        $fuenteMostrada = $partes['scheme'].'://'.$partes['host'];
        if (!empty($partes['port'])) {
            $fuenteMostrada .= ':'.$partes['port'];
        }
    }

    try {
        $requestHost = request()->getHost();
        $apiHost = parse_url($apiUrl, PHP_URL_HOST);

        if (!empty($apiHost) && $apiHost === $requestHost) {
            $response = Http::acceptJson()
                ->timeout(20)
                ->get('https://api.sampleapis.com/movies/comedy');
        } else {
            $response = Http::acceptJson()
                ->timeout(20)
                ->get($apiUrl);
        }

        $data = $response->successful() ? $response->json() : [];

        if (!is_array($data)) {
            $decoded = json_decode($response->body(), true);
            $data = is_array($decoded) ? $decoded : [];
        }
    } catch (\Throwable $e) {
        $mensaje = 'Error al consultar la API configurada.';
        $data = [];
    }

    if (empty($data)) {
        $mensaje = $mensaje ?? ('No se pudieron obtener datos desde la API configurada: ' . $apiUrl);
    }

    $enlace = $data;

    return view('viewmio', compact('enlace', 'mensaje', 'apiUrl', 'fuenteMostrada'));
}
```

### Explicacion teorica
- Se define una URL por defecto.
- Se puede sobrescribir con la variable de entorno `VIEW_MIO_API_URL`.
- Se obtiene el host para evitar que Render se llame a si mismo y se quede en un ciclo.
- Si la API configurada falla, se muestra un mensaje y no se rompe la pagina.
- Los datos se envian a la vista `viewmio`.

---

## 3. Vista local

La vista Blade es la que muestra la lista de titulos.

### Vista `viewmio.blade.php`
```php
<h1>Mi API Personal</h1>

@if(!empty($mensaje))
    <p style="background:#fff3cd; border:1px solid #ffe69c; padding:10px;">{{ $mensaje }}</p>
@endif

@forelse(($enlace ?? []) as $en)
    <div style="border:1px solid #ddd; margin:10px; padding:10px;">
        <h3 style="margin:0 0 8px 0;">{{ $en['title'] ?? 'Sin titulo' }}</h3>
    </div>
@empty
    <p>No hay datos disponibles desde la API.</p>
@endforelse
```

### Explicacion teorica
- `@forelse` recorre el arreglo de datos.
- Si hay datos, se muestra el titulo de cada pelicula.
- Si no hay datos, se muestra un mensaje vacio.
- La vista queda limpia y facil de entregar.

---

## 4. Flujo completo del proyecto

### En Render
1. El usuario entra a `https://lara23emi.onrender.com/`.
2. Laravel ejecuta `ApiComedyHosted`.
3. Esa funcion consulta la API externa de peliculas.
4. Render responde con JSON plano.

### En local
1. El usuario entra a `http://127.0.0.1:8000/viewmio`.
2. Laravel ejecuta `AccesoDatosViewMio`.
3. Esa funcion consume el hosting de Render.
4. La vista `viewmio.blade.php` muestra la lista de titulos.

---

## 5. Variables de entorno importantes

En Render se uso esta variable:

```env
VIEW_MIO_API_URL=https://api.sampleapis.com/movies/comedy
```

O tambien puede apuntar al hosting hospedado:

```env
VIEW_MIO_API_URL=https://lara23emi.onrender.com/api/comedy-hosted
```

---

## 6. Resumen final

Este proyecto cumple con lo pedido porque:

- Se creo un hosting en Render.
- El hosting entrega un JSON con peliculas.
- El proyecto local consume ese hosting.
- La pagina local muestra la informacion en una vista.
- El hosting puede responder JSON plano y la vista local puede estar formateada.

---

## 7. Enlaces de prueba

- Hosting JSON: `https://lara23emi.onrender.com/`
- Hosting API JSON: `https://lara23emi.onrender.com/api/comedy-hosted`
- Vista local formateada: `http://127.0.0.1:8000/viewmio`
