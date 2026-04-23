# Reporte Técnico de Entrega

## 1. Datos Generales del Proyecto

- Nombre del proyecto: API REST Laravel - Gestión de Productos y Categorías
- Estudiante: JOSUE CRISTOPHER GONZALEZ GARZA
- Stack tecnológico:
  - Laravel 11
  - PHP 8.2
  - PostgreSQL (Render)
  - MySQL (Local)
  - Docker
  - Postman
- Hosting: Render (Web Service con Docker)

## 2. Introducción

La solución desarrollada consiste en una API RESTful construida con Laravel 11 para administrar entidades de categorías y productos, con persistencia de datos y relaciones entre tablas.

El sistema permite ejecutar operaciones CRUD completas sobre ambos recursos, validando entradas, devolviendo respuestas estructuradas en formato JSON y códigos HTTP acordes al resultado de cada operación.

La arquitectura separa la capa de API de las rutas web del CRUD local, permitiendo:

- Uso local con MySQL para desarrollo y pruebas rápidas.
- Uso en producción sobre Render con PostgreSQL.
- Consumo desde clientes HTTP externos (Postman, frontend, integraciones).

## 3. Archivos Implicados y Código de la API

### 3.1 Archivos principales

- app/Http/Controllers/Api/CategoryApiController.php
- app/Http/Controllers/Api/ProductApiController.php
- routes/api.php
- app/Models/Category.php
- app/Models/Product.php
- database/seeders/DatabaseSeeder.php

### 3.2 Controlador de Categorías (GET, POST, PUT, DELETE)

Métodos REST implementados:

- GET: index(), show()
- POST: store()
- PUT/PATCH: update()
- DELETE: destroy()

~~~php
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class CategoryApiController extends Controller
{
    public function index(): JsonResponse
    {
        $categories = Category::query()
            ->withCount('products')
            ->orderBy('id', 'asc')
            ->get();

        return response()->json($categories, 200);
    }

    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255', 'unique:categories,name'],
            'description' => ['nullable', 'string'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation error.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $category = Category::create($validator->validated());

        return response()->json($category, 201);
    }

    public function show(int $id): JsonResponse
    {
        $category = Category::query()->with('products')->find($id);

        if (!$category) {
            return response()->json([
                'message' => 'Category not found.',
            ], 404);
        }

        return response()->json($category, 200);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $category = Category::query()->find($id);

        if (!$category) {
            return response()->json([
                'message' => 'Category not found.',
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => [
                'sometimes',
                'required',
                'string',
                'max:255',
                Rule::unique('categories', 'name')->ignore($category->id),
            ],
            'description' => ['sometimes', 'nullable', 'string'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation error.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $data = $validator->validated();

        if (empty($data)) {
            return response()->json([
                'message' => 'No fields provided for update.',
            ], 422);
        }

        $category->update($data);
        $category->refresh();

        return response()->json($category, 200);
    }

    public function destroy(int $id): JsonResponse
    {
        $category = Category::query()->withCount('products')->find($id);

        if (!$category) {
            return response()->json([
                'message' => 'Category not found.',
            ], 404);
        }

        if ($category->products_count > 0) {
            return response()->json([
                'message' => 'Cannot delete category with related products.',
            ], 422);
        }

        $category->delete();

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
~~~

### 3.3 Controlador de Productos (GET, POST, PUT, DELETE)

Métodos REST implementados:

- GET: index(), show()
- POST: store()
- PUT/PATCH: update()
- DELETE: destroy()

~~~php
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class ProductApiController extends Controller
{
    public function index(): JsonResponse
    {
        $products = Product::query()
            ->with('category')
            ->orderBy('id', 'asc')
            ->get();

        return response()->json($products, 200);
    }

    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:255'],
            'descriptionLong' => ['required', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'category_id' => ['nullable', 'exists:categories,id'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation error.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $product = Product::create($validator->validated());
        $product->load('category');

        return response()->json($product, 201);
    }

    public function show(int $id): JsonResponse
    {
        $product = Product::query()->with('category')->find($id);

        if (!$product) {
            return response()->json([
                'message' => 'Product not found.',
            ], 404);
        }

        return response()->json($product, 200);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $product = Product::query()->find($id);

        if (!$product) {
            return response()->json([
                'message' => 'Product not found.',
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'description' => ['sometimes', 'required', 'string', 'max:255'],
            'descriptionLong' => ['sometimes', 'required', 'string'],
            'price' => ['sometimes', 'required', 'numeric', 'min:0'],
            'category_id' => ['sometimes', 'nullable', 'exists:categories,id'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation error.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $data = $validator->validated();

        if (empty($data)) {
            return response()->json([
                'message' => 'No fields provided for update.',
            ], 422);
        }

        $product->update($data);
        $product->refresh();
        $product->load('category');

        return response()->json($product, 200);
    }

    public function destroy(int $id): JsonResponse
    {
        $product = Product::query()->find($id);

        if (!$product) {
            return response()->json([
                'message' => 'Product not found.',
            ], 404);
        }

        $product->delete();

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
~~~

### 3.4 Definición de rutas de API

~~~php
<?php

use App\Http\Controllers\Api\CategoryApiController;
use App\Http\Controllers\Api\ProductApiController;
use Illuminate\Support\Facades\Route;

Route::apiResource('categories', CategoryApiController::class)->names('api.categories');
Route::apiResource('products', ProductApiController::class)->names('api.products');
~~~

### 3.5 Modelos involucrados

~~~php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory; 

    protected $fillable = [
        'name',
        'description',
    ];

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
~~~

~~~php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Category;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'descriptionLong',
        'price',
        'category_id',
    ];

    //  RELACIÓN
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
~~~

### 3.6 Seeder principal

~~~php
<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
         Category::factory(7)->create()->each(function ($category) {
        Product::factory(30)->create([
            'category_id' => $category->id
        ]);
    });
    }
}
~~~

## 4. Proceso de Despliegue en Render

### 4.1 Configuración de Dockerfile para producción

Se define un Dockerfile orientado a ejecución en entorno cloud, incluyendo:

- Imagen base de PHP 8.2 compatible con Laravel 11.
- Instalación de extensiones requeridas para base de datos y framework.
- Copia del código fuente y dependencias de Composer.
- Exposición del puerto asignado por Render.
- Comando de arranque del servicio web.

Resultado esperado: la aplicación inicia en Render en modo producción y queda accesible por URL pública.

### 4.2 Conexión con PostgreSQL externa

Se configura Render para usar una base PostgreSQL administrada externamente (o servicio PostgreSQL de la misma plataforma), definiendo:

- DB_CONNECTION=pgsql
- DB_HOST
- DB_PORT
- DB_DATABASE
- DB_USERNAME
- DB_PASSWORD

Resultado esperado: Laravel migra desde MySQL local a PostgreSQL en producción sin cambiar lógica de negocio.

### 4.3 Variables de entorno en Dashboard de Render

Variables críticas recomendadas:

- APP_NAME
- APP_ENV=production
- APP_DEBUG=false
- APP_URL=https://tu-servicio.onrender.com
- APP_KEY
- DB_CONNECTION=pgsql
- DB_HOST
- DB_PORT
- DB_DATABASE
- DB_USERNAME
- DB_PASSWORD
- LOG_CHANNEL=stack
- LOG_LEVEL=error

Resultado esperado: configuración segura, comportamiento estable y trazabilidad mediante logs.

### 4.4 Migraciones y seeders desde Shell de Render

Comandos de despliegue de datos:

~~~bash
php artisan migrate --force
php artisan db:seed --force
~~~

El parámetro --force permite la ejecución en entorno productivo sin confirmación interactiva.

Resultado esperado:

- Estructura de tablas creada correctamente.
- Datos de prueba cargados en categorías y productos.

## 5. Guía de Pruebas en Postman

### 5.1 Evidencia requerida 1: GET para listar categorías

- Método: GET
- Endpoint: https://tu-servicio.onrender.com/api/categories
- Resultado esperado: lista JSON de categorías con products_count
- Código HTTP esperado: 200 OK

Evidencia a adjuntar en el reporte final:

- Captura de pantalla de la petición ejecutada.
- Captura visible del código 200.

### 5.2 Evidencia requerida 2: POST con Body JSON para crear producto

- Método: POST
- Endpoint: https://tu-servicio.onrender.com/api/products
- Header: Content-Type: application/json
- Body JSON de ejemplo:

~~~json
{
  "name": "Producto Demo",
  "description": "Descripción corta demo",
  "descriptionLong": "Descripción larga de prueba para validación en Render",
  "price": 49.90,
  "category_id": 1
}
~~~

- Resultado esperado: objeto del producto creado con su categoría.
- Código HTTP esperado: 201 Created.

Evidencia a adjuntar:

- Captura del body enviado.
- Captura de la respuesta JSON con código 201.

### 5.3 Explicación de endpoint onrender.com y códigos HTTP

URL base de producción:

- https://tu-servicio.onrender.com

Base path de API:

- https://tu-servicio.onrender.com/api

Interpretación de códigos:

- 200 OK: operación consultiva o actualización exitosa con respuesta.
- 201 Created: recurso creado exitosamente.
- 204 No Content: eliminación exitosa sin cuerpo de respuesta.
- 404 Not Found: recurso inexistente.
- 422 Unprocessable Entity: error de validación de datos.

## 6. Conclusión

La API RESTful implementada en Laravel 11 cumple los criterios de una solución backend profesional para gestión de productos y categorías:

- Estructura REST clara y consistente.
- Validaciones robustas en creación y actualización.
- Manejo de relaciones entre entidades.
- Separación adecuada entre entorno local y despliegue en Render.
- Pruebas verificables con Postman y códigos HTTP estandarizados.

El proyecto queda apto para uso académico, demostración técnica y extensión futura hacia autenticación, versionado de API o integración con frontend desacoplado.
