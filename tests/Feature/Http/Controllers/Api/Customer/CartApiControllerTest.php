<?php

namespace Tests\Feature\Http\Controllers\Api\Customer;

use App\Models\Part;
use App\Models\PartOption;
use App\Models\ProductType;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('guest can add item to cart', function () {
    $product = ProductType::factory()->create(['active' => true]);

    $part = Part::factory()->create([
        'product_type_id' => $product->id,
        'name' => 'Part 1',
        'required' => true,
        'active' => true,
    ]);

    $option = PartOption::factory()->create([
        'part_id' => $part->id,
        'name' => 'Option 1',
        'base_price' => 100.00,
        'active' => true,
        'in_stock' => true,
    ]);

    $cartId = 'test-cart-id';
    $data = [
        'cart_id' => $cartId,
        'product_id' => $product->id,
        'selected_options' => [
            'options' => [
                $part->id => [
                    'partId' => $part->id,
                    'partName' => 'Part 1',
                    'optionId' => $option->id,
                    'optionName' => 'Option 1',
                    'price' => 100.00
                ]
            ]
        ],
        'price_adjustments' => [],
        'total_price' => 100.00,
    ];

    $response = $this->postJson('/api/cart', $data);

    $response->assertStatus(200)
        ->assertJson([
            'message' => 'Item added to cart successfully',
        ]);

    $this->assertDatabaseHas('carts', [
        'cart_id' => $cartId,
        'product_id' => $product->id,
        'user_id' => null,
        'total_price' => 100.00,
    ]);
});

test('authenticated user can add item to cart', function () {
    $user = User::factory()->create();
    $product = ProductType::factory()->create(['active' => true]);

    $part = Part::factory()->create([
        'product_type_id' => $product->id,
        'name' => 'Part 1',
        'required' => true,
        'active' => true,
    ]);

    $option = PartOption::factory()->create([
        'part_id' => $part->id,
        'name' => 'Option 1',
        'base_price' => 100.00,
        'active' => true,
        'in_stock' => true,
    ]);

    $cartId = 'test-cart-id';
    $data = [
        'cart_id' => $cartId,
        'product_id' => $product->id,
        'selected_options' => [
            'options' => [
                $part->id => [
                    'partId' => $part->id,
                    'partName' => 'Part 1',
                    'optionId' => $option->id,
                    'optionName' => 'Option 1',
                    'price' => 100.00
                ]
            ]
        ],
        'price_adjustments' => [],
        'total_price' => 100.00,
    ];

    $response = $this->actingAs($user)
        ->postJson('/api/cart', $data);

    $response->assertStatus(200)
        ->assertJson([
            'message' => 'Item added to cart successfully',
        ]);

    $this->assertDatabaseHas('carts', [
        'cart_id' => $cartId,
        'product_id' => $product->id,
        'user_id' => $user->id,
        'total_price' => 100.00,
    ]);
});

test('validation fails with invalid data', function () {
    $cartId = 'test-cart-id';
    $data = [
        'cart_id' => $cartId,
        'selected_options' => [
            'options' => []
        ],
        'total_price' => 'not-a-number',
    ];

    $response = $this->postJson('/api/cart', $data);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['product_id', 'total_price']);
});

test('can get existing cart', function () {
    $product = ProductType::factory()->create(['active' => true]);
    $cartId = 'test-cart-id';

    // Create a cart item
    $cart = \App\Models\Cart::factory()->create([
        'cart_id' => $cartId,
        'product_id' => $product->id,
        'user_id' => null,
    ]);

    $response = $this->getJson('/api/cart/validate?cart_id=' . $cartId);

    $response->assertStatus(200)
        ->assertJson([
            'exists' => true,
        ])
        ->assertJsonStructure([
            'exists',
            'cart_items' => [
                '*' => [
                    'id',
                    'cart_id',
                    'product_id',
                    'selected_options',
                    'total_price',
                    'product' => [
                        'id',
                        'name'
                    ]
                ]
            ]
        ]);
});

test('returns not found for non-existent cart', function () {
    $cartId = 'non-existent-cart-id';

    $response = $this->getJson('/api/cart/validate?cart_id=' . $cartId);

    $response->assertStatus(200)
        ->assertJson([
            'exists' => false,
            'message' => 'Cart not found'
        ]);
});

test('returns error for missing cart_id', function () {
    $response = $this->getJson('/api/cart/validate');

    $response->assertStatus(400)
        ->assertJson([
            'exists' => false,
            'message' => 'Cart ID is required'
        ]);
});
