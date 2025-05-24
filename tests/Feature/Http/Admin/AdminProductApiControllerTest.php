<?php

use App\Models\Admin;
use App\Models\Part;
use App\Models\PartOption;
use App\Models\ProductType;
use Laravel\Sanctum\Sanctum;

beforeEach(function () {
    $admin = Admin::factory()->create();
    Sanctum::actingAs($admin, ['*'], 'admin');
});

test('it lists all products', function () {
    ProductType::factory()->count(3)->create();

    $response = $this->getJson(route('admin.products.index'));

    $response->assertStatus(200)
        ->assertJsonStructure([
            'products'
        ])
        ->assertJsonCount(3, 'products');
});

test('it creates a new product', function () {
    $productData = [
        'name' => 'Test Product',
        'description' => 'Test Description',
        'active' => true,
    ];

    $response = $this->postJson(route('admin.products.store'), $productData);

    $response->assertStatus(201)
        ->assertJsonStructure([
            'message',
            'product'
        ])
        ->assertJson([
            'message' => 'Product created successfully.',
            'product' => [
                'name' => 'Test Product',
                'description' => 'Test Description',
                'active' => 1,
            ]
        ]);

    $this->assertDatabaseHas('product_types', [
        'name' => 'Test Product',
        'description' => 'Test Description',
    ]);
});

test('it shows a specific product', function () {
    $product = ProductType::factory()->create();

    $response = $this->getJson(route('admin.products.show', $product));

    $response->assertStatus(200)
        ->assertJsonStructure([
            'product'
        ])
        ->assertJson([
            'product' => [
                'id' => $product->id,
                'name' => $product->name,
            ]
        ]);
});

test('it updates a product', function () {
    $product = ProductType::factory()->create();

    $updateData = [
        'name' => 'Updated Product',
        'description' => 'Updated Description',
        'active' => false,
    ];

    $response = $this->putJson(route('admin.products.update', $product), $updateData);

    $response->assertStatus(200)
        ->assertJsonStructure([
            'message',
            'product'
        ])
        ->assertJson([
            'message' => 'Product updated successfully.',
            'product' => [
                'id' => $product->id,
                'name' => 'Updated Product',
                'description' => 'Updated Description',
            ]
        ]);

    $this->assertDatabaseHas('product_types', [
        'id' => $product->id,
        'name' => 'Updated Product',
        'description' => 'Updated Description',
    ]);
});

test('it deletes a product', function () {
    $product = ProductType::factory()->create();

    $response = $this->deleteJson(route('admin.products.destroy', $product));

    $response->assertStatus(200)
        ->assertJsonStructure([
            'message'
        ])
        ->assertJson([
            'message' => 'Product deleted successfully.'
        ]);

    $this->assertDatabaseMissing('product_types', [
        'id' => $product->id,
    ]);
});

test('it requires authentication for all endpoints', function () {
    auth()->forgetGuards();
    $product = ProductType::factory()->create();

    $this->getJson(route('admin.products.index'))->assertStatus(401);
    $this->postJson(route('admin.products.store'), [])->assertStatus(401);
    $this->getJson(route('admin.products.show', $product))->assertStatus(401);
    $this->putJson(route('admin.products.update', $product), [])->assertStatus(401);
    $this->deleteJson(route('admin.products.destroy', $product))->assertStatus(401);
});
