<?php

use App\Actions\Admin\PartOption\GetPartOptionAction;
use App\Models\Part;
use App\Models\PartOption;
use App\Models\ProductType;

test('it retrieves an option with its part and product type', function () {
    $productType = ProductType::factory()->create();
    $part = Part::factory()->create([
        'product_type_id' => $productType->id,
    ]);
    $option = PartOption::factory()->create([
        'part_id' => $part->id,
        'name' => 'Test Option',
        'description' => 'Test Description',
        'base_price' => 50.00,
        'display_order' => 1,
        'in_stock' => true,
        'active' => true,
    ]);

    $action = new GetPartOptionAction();

    $result = $action->handle($option);

    expect($result)->toBeArray()
        ->toHaveKeys(['option', 'part', 'product'])
        ->and($result['option']->id)->toBe($option->id)
        ->and($result['part']->id)->toBe($part->id)
        ->and($result['product']->id)->toBe($productType->id);

    expect($result['option']->relationLoaded('part'))->toBeTrue()
        ->and($result['part']->relationLoaded('productType'))->toBeTrue();
});