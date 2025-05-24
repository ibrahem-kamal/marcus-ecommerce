<?php

use App\Actions\Admin\Part\GetPartAction;
use App\Models\Part;
use App\Models\ProductType;

test('it retrieves a part with its options and product type', function () {
    $productType = ProductType::factory()->create();

    $part = Part::factory()->create([
        'product_type_id' => $productType->id,
        'name' => 'Test Part',
        'description' => 'Test Description',
        'display_order' => 1,
        'required' => true,
        'active' => true,
    ]);

    $action = new GetPartAction();

    $result = $action->handle($part);

    expect($result)->toBeArray()
        ->toHaveKeys(['part', 'product'])
        ->and($result['part']->id)->toBe($part->id)
        ->and($result['product']->id)->toBe($productType->id);

    expect($result['part']->relationLoaded('options'))->toBeTrue()
        ->and($result['part']->relationLoaded('productType'))->toBeTrue();
});
