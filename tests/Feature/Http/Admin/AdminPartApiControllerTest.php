<?php

use App\Models\Admin;
use App\Models\Part;
use App\Models\ProductType;
use Laravel\Sanctum\Sanctum;

beforeEach(function () {
    $admin = Admin::factory()->create();
    Sanctum::actingAs($admin, ['*']);
});

test('it creates a new part for a product', function () {
    $product = ProductType::factory()->create();

    $partData = [
        'name' => 'Test Part',
        'description' => 'Test Description',
        'display_order' => 1,
        'required' => true,
        'active' => true,
    ];

    $response = $this->postJson(route('admin.products.parts.store', $product), $partData);

    $response->assertStatus(201)
        ->assertJsonStructure([
            'message',
            'part'
        ])
        ->assertJson([
            'message' => 'Part added successfully.',
            'part' => [
                'name' => 'Test Part',
                'description' => 'Test Description',
                'product_type_id' => $product->id,
            ]
        ]);

    $this->assertDatabaseHas('parts', [
        'name' => 'Test Part',
        'description' => 'Test Description',
        'product_type_id' => $product->id,
    ]);
});

test('it shows a specific part', function () {
    $product = ProductType::factory()->create();
    $part = Part::factory()->create(['product_type_id' => $product->id]);

    $response = $this->getJson(route('admin.parts.show', $part));

    $response->assertStatus(200)
        ->assertJsonStructure([
            'part',
            'product'
        ])
        ->assertJson([
            'part' => [
                'id' => $part->id,
                'name' => $part->name,
            ],
            'product' => [
                'id' => $product->id,
                'name' => $product->name,
            ]
        ]);
});

test('it updates a part', function () {
    $product = ProductType::factory()->create();
    $part = Part::factory()->create(['product_type_id' => $product->id]);

    $updateData = [
        'name' => 'Updated Part',
        'description' => 'Updated Description',
        'display_order' => 2,
        'required' => false,
        'active' => true,
    ];

    $response = $this->putJson(route('admin.parts.update', $part), $updateData);

    $response->assertStatus(200)
        ->assertJsonStructure([
            'message',
            'part'
        ])
        ->assertJson([
            'message' => 'Part updated successfully.',
            'part' => [
                'id' => $part->id,
                'name' => 'Updated Part',
                'description' => 'Updated Description',
            ]
        ]);

    $this->assertDatabaseHas('parts', [
        'id' => $part->id,
        'name' => 'Updated Part',
        'description' => 'Updated Description',
    ]);
});

test('it deletes a part', function () {
    $product = ProductType::factory()->create();
    $part = Part::factory()->create(['product_type_id' => $product->id]);

    $response = $this->deleteJson(route('admin.parts.destroy', $part));

    $response->assertStatus(200)
        ->assertJsonStructure([
            'message'
        ])
        ->assertJson([
            'message' => 'Part deleted successfully.'
        ]);

    $this->assertDatabaseMissing('parts', [
        'id' => $part->id,
    ]);
});