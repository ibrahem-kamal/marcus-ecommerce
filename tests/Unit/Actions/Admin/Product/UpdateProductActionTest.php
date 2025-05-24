<?php

use App\Actions\Admin\Product\UpdateProductAction;
use App\Models\ProductType;

test('it updates a product', function () {
    $product = ProductType::factory()->create([
        'name' => 'Test Product',
        'description' => 'Test Description',
        'active' => true,
    ]);

    $productData = [
        'name' => 'Updated Product',
        'description' => 'Updated Description',
        'active' => false,
    ];

    $action = new UpdateProductAction();

    $result = $action->handle($product, $productData);

    expect($result)->toBeArray()
        ->toHaveKeys(['message', 'product'])
        ->and($result['message'])->toBe('Product updated successfully.')
        ->and($result['product']->id)->toBe($product->id);

    $updatedProduct = ProductType::find($product->id);
    expect($updatedProduct->name)->toBe($productData['name'])
        ->and($updatedProduct->description)->toBe($productData['description'])
        ->and($updatedProduct->active)->toBe($productData['active']);
});
