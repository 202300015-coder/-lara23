# Documento Final - API REST con Laravel (Productos y Categorias)

## 1. Contexto del trabajo

Se requiere desarrollar una API RESTful en Laravel para administrar **categorias** y **productos** con operaciones CRUD completas, probar todos los endpoints en Postman y trabajar con base de datos en hosting (Render) usando:

- URL de produccion: https://lara23emi.onrender.com

---

## 2. Analisis de lo que YA tienes en este proyecto

### 2.1 Estructura y datos

Ya existen modelos y migraciones para las dos entidades:

- `Category` con campos: `id`, `name`, `description`, timestamps.
- `Product` con campos: `id`, `name`, `description`, `descriptionLong`, `price`, `category_id`, timestamps.

Tambien ya existe la relacion:

- Una categoria tiene muchos productos.
- Un producto pertenece a una categoria.

### 2.2 CRUD actual implementado

Actualmente el CRUD de categorias y productos esta implementado en **rutas web** (`routes/web.php`) y controladores que devuelven **vistas Blade** (`view(...)`), no respuestas JSON de API.

Esto significa:

- Ya tienes logica base de validacion y persistencia.
- Aun falta separar/crear la capa API REST JSON (endpoints estilo `/api/...`).

### 2.3 Situacion respecto al enunciado

Con base en el requerimiento de API REST + Postman, lo que falta para cumplimiento total es:

1. Endpoints API JSON para categorias y productos.
2. Respuestas HTTP correctas con codigos de estado.
3. Pruebas documentadas en Postman para cada endpoint.
4. Evidencia de funcionamiento en Render.

---

## 3. Objetivo tecnico final

Implementar y dejar operativa una API con este alcance minimo:

### CRUD de categorias

- Crear categoria
- Listar categorias
- Consultar categoria por id
- Actualizar categoria
- Eliminar categoria

### CRUD de productos

- Crear producto
- Listar productos
- Consultar producto por id
- Actualizar producto
- Eliminar producto

---

## 4. Pasos que se tienen que hacer (plan de implementacion)

## Paso 1: Crear rutas API

En `routes/api.php` definir rutas REST (recomendado con `apiResource`):

- `Route::apiResource('categories', CategoryApiController::class);`
- `Route::apiResource('products', ProductApiController::class);`

Con esto obtienes automaticamente:

- `GET /api/categories`
- `POST /api/categories`
- `GET /api/categories/{id}`
- `PUT/PATCH /api/categories/{id}`
- `DELETE /api/categories/{id}`

y lo mismo para products.

## Paso 2: Crear controladores API (separados de los web)

Crear por ejemplo:

- `App\Http\Controllers\Api\CategoryApiController`
- `App\Http\Controllers\Api\ProductApiController`

Cada metodo debe devolver JSON:

- `index()` -> lista
- `store()` -> crear
- `show($id)` -> detalle
- `update($id)` -> actualizar
- `destroy($id)` -> eliminar

## Paso 3: Validacion de datos

Aplicar validacion en `store` y `update`.

Reglas sugeridas:

- Categoria:
  - `name`: required|string|max:255
  - `description`: nullable|string
- Producto:
  - `name`: required|string|max:255
  - `description`: required|string|max:255
  - `descriptionLong`: required|string
  - `price`: required|numeric|min:0
  - `category_id`: nullable|exists:categories,id

## Paso 4: Estandar de respuestas HTTP

Usar codigos de estado correctos:

- `200 OK`: consultas y actualizaciones exitosas
- `201 Created`: creacion exitosa
- `404 Not Found`: recurso no existe
- `422 Unprocessable Entity`: validacion falla
- `500 Internal Server Error`: error inesperado

## Paso 5: Formato JSON consistente

Recomendado:

- En exito: `message`, `data`
- En error: `message`, y si aplica `errors`

Ejemplo de creacion:

```json
{
  "message": "Categoria creada correctamente",
  "data": {
    "id": 1,
    "name": "Bebidas",
    "description": "Categoria de bebidas"
  }
}
```

## Paso 6: Despliegue en Render

Validar en Render:

1. Variables de entorno (`APP_KEY`, `APP_ENV=production`, `APP_DEBUG=false`, conexion DB).
2. Migraciones ejecutadas en produccion:
   - `php artisan migrate --force`
3. Si hay seeders de prueba:
   - `php artisan db:seed --force`
4. Limpiar cache:
   - `php artisan optimize:clear`

## Paso 7: Verificacion final en URL publica

Probar endpoints con base:

- `https://lara23emi.onrender.com/api/categories`
- `https://lara23emi.onrender.com/api/products`

---

## 5. Pruebas con Postman (lo que debes evidenciar)

Para cada endpoint mostrar:

1. Metodo HTTP.
2. URL completa.
3. Body en JSON (cuando aplique).
4. Respuesta JSON.
5. Codigo HTTP correcto.
6. Evidencia de funcionamiento (capturas).

### 5.1 Coleccion recomendada en Postman

Crear carpeta `Categorias` y `Productos`.

#### Categorias

1. `POST /api/categories`
- Body JSON:

```json
{
  "name": "Tecnologia",
  "description": "Dispositivos y accesorios"
}
```

2. `GET /api/categories`
3. `GET /api/categories/{id}`
4. `PUT /api/categories/{id}`
- Body JSON:

```json
{
  "name": "Tecnologia Actualizada",
  "description": "Categoria editada"
}
```

5. `DELETE /api/categories/{id}`

#### Productos

1. `POST /api/products`
- Body JSON:

```json
{
  "name": "Laptop Pro",
  "description": "Laptop de alto rendimiento",
  "descriptionLong": "Equipo para desarrollo, diseno y tareas exigentes",
  "price": 1999.99,
  "category_id": 1
}
```

2. `GET /api/products`
3. `GET /api/products/{id}`
4. `PUT /api/products/{id}`
- Body JSON:

```json
{
  "name": "Laptop Pro 2026",
  "description": "Laptop actualizada",
  "descriptionLong": "Nueva version con mejor rendimiento",
  "price": 2199.99,
  "category_id": 1
}
```

5. `DELETE /api/products/{id}`

### 5.2 Pruebas negativas obligatorias

Tambien conviene evidenciar:

- `GET /api/categories/999999` -> 404
- `POST /api/products` sin campos requeridos -> 422
- `POST /api/products` con `category_id` inexistente -> 422

---

## 6. Criterios de cumplimiento de la tarea

La entrega queda completa cuando:

1. Existen endpoints CRUD JSON para categorias y productos.
2. Todos responden correctamente en local y en Render.
3. Postman muestra casos exitosos y casos de error controlado.
4. Hay evidencia (capturas) de metodo, URL, body, respuesta y status.
5. La base de datos en hosting guarda y refleja los cambios CRUD.

---

## 7. Resultado esperado de evaluacion

Con esta implementacion, el proyecto demuestra:

- Arquitectura RESTful en Laravel.
- Persistencia real en base de datos en hosting.
- Validacion y manejo de errores HTTP.
- Pruebas funcionales completas con Postman.

En resumen: ya tienes una base CRUD web funcional; ahora el enfoque final es exponer esa misma logica como API JSON en `/api`, validarla con Postman y comprobarla en Render.