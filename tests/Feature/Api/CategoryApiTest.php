<?php

use App\Models\Category;
use App\Models\Product;

it('lists categories as json', function () {
    /** @var \Tests\TestCase $this */
    Category::factory()->count(2)->create();

    $response = $this->getJson('/api/categories');

    $response->assertStatus(200)
        ->assertJsonCount(2);
});

it('creates a category', function () {
    /** @var \Tests\TestCase $this */
    $payload = [
        'name' => 'Bebidas',
        'description' => 'Categoria de bebidas',
    ];

    $response = $this->postJson('/api/categories', $payload);

    $response->assertStatus(201)
        ->assertJsonFragment(['name' => 'Bebidas']);

    $this->assertDatabaseHas('categories', [
        'name' => 'Bebidas',
    ]);
});

it('returns 422 for invalid category payload', function () {
    /** @var \Tests\TestCase $this */
    $response = $this->postJson('/api/categories', [
        'name' => '',
    ]);

    $response->assertStatus(422)
        ->assertJsonStructure(['message', 'errors']);
});

it('shows one category', function () {
    /** @var \Tests\TestCase $this */
    $category = Category::factory()->create();

    $response = $this->getJson('/api/categories/'.$category->id);

    $response->assertStatus(200)
        ->assertJsonFragment(['id' => $category->id]);
});

it('returns 404 when category does not exist', function () {
    /** @var \Tests\TestCase $this */
    $response = $this->getJson('/api/categories/999999');

    $response->assertStatus(404);
});

it('updates a category', function () {
    /** @var \Tests\TestCase $this */
    $category = Category::factory()->create();

    $response = $this->putJson('/api/categories/'.$category->id, [
        'name' => 'Actualizada',
        'description' => 'Descripcion actualizada',
    ]);

    $response->assertStatus(200)
        ->assertJsonFragment(['name' => 'Actualizada']);

    $this->assertDatabaseHas('categories', [
        'id' => $category->id,
        'name' => 'Actualizada',
    ]);
});

it('deletes a category with no products', function () {
    /** @var \Tests\TestCase $this */
    $category = Category::factory()->create();

    $response = $this->deleteJson('/api/categories/'.$category->id);

    $response->assertStatus(204);

    $this->assertDatabaseMissing('categories', [
        'id' => $category->id,
    ]);
});

it('returns 422 when deleting category with products', function () {
    /** @var \Tests\TestCase $this */
    $category = Category::factory()->create();
    Product::factory()->create(['category_id' => $category->id]);

    $response = $this->deleteJson('/api/categories/'.$category->id);

    $response->assertStatus(422);
});
