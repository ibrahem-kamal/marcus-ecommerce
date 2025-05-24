<?php

use App\Models\Admin;
use App\Models\Part;
use App\Models\PartOption;
use App\Models\ProductType;
use Laravel\Sanctum\Sanctum;

beforeEach(function () {
    $admin = Admin::factory()->create();
    Sanctum::actingAs($admin, ['*']);
});

test('it creates a new option for a part', function () {
    $product = ProductType::factory()->create();
    $part = Part::factory()->create(['product_type_id' => $product->id]);

    $optionData = [
        'name' => 'Test Option',
        'description' => 'Test Description',
        'base_price' => 50.00,
        'display_order' => 1,
        'in_stock' => true,
        'active' => true,
    ];

    $response = $this->postJson(route('admin.parts.options.store', $part), $optionData);

    $response->assertStatus(201)
        ->assertJsonStructure([
            'message',
            'option'
        ])
        ->assertJson([
            'message' => 'Option added successfully.',
            'option' => [
                'name' => 'Test Option',
                'description' => 'Test Description',
                'part_id' => $part->id,
            ]
        ]);

    $this->assertDatabaseHas('part_options', [
        'name' => 'Test Option',
        'description' => 'Test Description',
        'part_id' => $part->id,
    ]);
});

test('it shows a specific option', function () {
    $product = ProductType::factory()->create();
    $part = Part::factory()->create(['product_type_id' => $product->id]);
    $option = PartOption::factory()->create(['part_id' => $part->id]);

    $response = $this->getJson(route('admin.options.show', $option));

    $response->assertStatus(200)
        ->assertJsonStructure([
            'option',
            'part',
            'product'
        ])
        ->assertJson([
            'option' => [
                'id' => $option->id,
                'name' => $option->name,
            ],
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

test('it updates an option', function () {
    $product = ProductType::factory()->create();
    $part = Part::factory()->create(['product_type_id' => $product->id]);
    $option = PartOption::factory()->create(['part_id' => $part->id]);

    $updateData = [
        'name' => 'Updated Option',
        'description' => 'Updated Description',
        'base_price' => 75.00,
        'display_order' => 2,
        'in_stock' => false,
        'active' => true,
    ];

    $response = $this->putJson(route('admin.options.update', $option), $updateData);

    $response->assertStatus(200)
        ->assertJsonStructure([
            'message',
            'option'
        ])
        ->assertJson([
            'message' => 'Option updated successfully.',
            'option' => [
                'id' => $option->id,
                'name' => 'Updated Option',
                'description' => 'Updated Description',
            ]
        ]);

    $this->assertDatabaseHas('part_options', [
        'id' => $option->id,
        'name' => 'Updated Option',
        'description' => 'Updated Description',
    ]);
});

test('it deletes an option', function () {
    $product = ProductType::factory()->create();
    $part = Part::factory()->create(['product_type_id' => $product->id]);
    $option = PartOption::factory()->create(['part_id' => $part->id]);

    $response = $this->deleteJson(route('admin.options.destroy', $option));

    $response->assertStatus(200)
        ->assertJsonStructure([
            'message'
        ])
        ->assertJson([
            'message' => 'Option deleted successfully.'
        ]);

    $this->assertDatabaseMissing('part_options', [
        'id' => $option->id,
    ]);
});