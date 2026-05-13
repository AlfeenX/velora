<?php

use App\Models\Product;
use App\Models\User;

test('it can list products', function () {
    Product::factory()->count(3)->create();

    $response = $this->getJson('/products');

    $response->assertStatus(200)
             ->assertJsonStructure(['data', 'links', 'meta']);
});

test('admin can create a product', function () {
    $user = User::factory()->create();
    $category = \App\Models\Category::factory()->create();

    $response = $this->actingAs($user)
        ->postJson('/admin/products', [
            'name' => 'New Product',
            'slug' => 'new-product',
            'description' => 'Product description',
            'category_id' => $category->id,
            'gender' => 'unisex',
            'variants' => [
                [
                    'sku' => 'NP-001',
                    'color' => 'Black',
                    'size' => 'L',
                    'price' => 1000,
                    'stock' => 10,
                ]
            ]
        ]);

    $response->assertStatus(201);
});
