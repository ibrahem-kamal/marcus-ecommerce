<?php

use App\Actions\Admin\Product\CreateProductAction;
use App\Models\ProductType;

test('it creates a new product', function () {
    $productData = [
        'name' => 'Test Product',
        'description' => 'Test Description',
        'active' => true,
    ];

    $createdProduct = ProductType::factory()->make($productData);


    $action = new CreateProductAction();

    $result = $action->handle($productData);
    $storedProduct = ProductType::where('name', $productData['name'])->first();
    
    expect($result)->toBeArray()
        ->toHaveKeys(['message', 'product'])
        ->and($result['message'])->toBe('Product created successfully.')
        ->and($result['product']['name'])->toBe($createdProduct->name);
    
    expect($storedProduct)->not->toBeNull()
        ->and($storedProduct->name)->toBe($createdProduct->name)
        ->and($storedProduct->description)->toBe($createdProduct->description)
        ->and($storedProduct->active)->toBe($createdProduct->active);
});
