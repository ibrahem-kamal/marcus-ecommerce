<?php

use App\Actions\Admin\Product\GetProductAction;
use App\Models\Part;
use App\Models\PartOption;
use App\Models\ProductType;

test('it retrieves a product with its parts and options', function () {
    $product = ProductType::factory()->create([
        'name' => 'Test Product',
        'description' => 'Test Description',
    ]);

    $part = Part::factory()->create([
        'product_type_id' => $product->id,
        'name' => 'Test Part',
        'description' => 'Test Part Description',
        'display_order' => 1,
        'required' => true,
        'active' => true,
    ]);

    $option = PartOption::factory()->create([
        'part_id' => $part->id,
        'name' => 'Test Option',
        'description' => 'Test Option Description',
        'base_price' => 10.99,
        'display_order' => 1,
        'active' => true,
    ]);

    $action = new GetProductAction();

    $result = $action->handle($product);

    expect($result)->toBeArray()
        ->toHaveKey('product')
        ->and($result['product']->id)->toBe($product->id);

    expect($result['product']->relationLoaded('parts'))->toBeTrue();

    expect($result['product']->parts)->toHaveCount(1)
        ->and($result['product']->parts[0]->id)->toBe($part->id);

    expect($result['product']->parts[0]->relationLoaded('options'))->toBeTrue();

    expect($result['product']->parts[0]->options)->toHaveCount(1)
        ->and($result['product']->parts[0]->options[0]->id)->toBe($option->id);
});
