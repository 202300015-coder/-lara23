<?php

use App\Models\Category;
use App\Models\Product;

it('lists products as json', function () {
    /** @var \Tests\TestCase $this */
    Product::factory()->count(2)->create();

    $response = $this->getJson('/api/products');

    $response->assertStatus(200)
        ->assertJsonCount(2);
});

it('creates a product', function () {
    /** @var \Tests\TestCase $this */
    $category = Category::factory()->create();

    $payload = [
        'name' => 'Coca Cola',
        'description' => 'Bebida gaseosa',
        'descriptionLong' => 'Bebida de 500ml',
        'price' => 2.5,
        'category_id' => $category->id,
    ];

    $response = $this->postJson('/api/products', $payload);

    $response->assertStatus(201)
        ->assertJsonFragment(['name' => 'Coca Cola']);

    $this->assertDatabaseHas('products', [
        'name' => 'Coca Cola',
    ]);
});

it('returns 422 for invalid product payload', function () {
    /** @var \Tests\TestCase $this */
    $response = $this->postJson('/api/products', [
        'name' => '',
    ]);

    $response->assertStatus(422)
        ->assertJsonStructure(['message', 'errors']);
});

it('shows one product', function () {
    /** @var \Tests\TestCase $this */
    $product = Product::factory()->create();

    $response = $this->getJson('/api/products/'.$product->id);

    $response->assertStatus(200)
        ->assertJsonFragment(['id' => $product->id]);
});

it('returns 404 when product does not exist', function () {
    /** @var \Tests\TestCase $this */
    $response = $this->getJson('/api/products/999999');

    $response->assertStatus(404);
});

it('updates a product', function () {
    /** @var \Tests\TestCase $this */
    $category = Category::factory()->create();
    $product = Product::factory()->create();

    $response = $this->putJson('/api/products/'.$product->id, [
        'name' => 'Actualizado',
        'description' => 'Desc',
        'descriptionLong' => 'Descripcion larga actualizada',
        'price' => 9.99,
        'category_id' => $category->id,
    ]);

    $response->assertStatus(200)
        ->assertJsonFragment(['name' => 'Actualizado']);

    $this->assertDatabaseHas('products', [
        'id' => $product->id,
        'name' => 'Actualizado',
    ]);
});

it('deletes a product', function () {
    /** @var \Tests\TestCase $this */
    $product = Product::factory()->create();

    $response = $this->deleteJson('/api/products/'.$product->id);

    $response->assertStatus(204);

    $this->assertDatabaseMissing('products', [
        'id' => $product->id,
    ]);
});
