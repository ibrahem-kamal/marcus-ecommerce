<?php

use App\Actions\Admin\Product\DeleteProductAction;
use App\Models\ProductType;

test('it deletes a product', function () {
    $product = ProductType::factory()->create([
        'name' => 'Test Product',
        'description' => 'Test Description',
    ]);

    $productId = $product->id;

    $action = new DeleteProductAction();

    $result = $action->handle($product);

    expect($result)->toBeArray()
        ->toHaveKey('message')
        ->and($result['message'])->toBe('Product deleted successfully.');

    expect(ProductType::find($productId))->toBeNull();
});
