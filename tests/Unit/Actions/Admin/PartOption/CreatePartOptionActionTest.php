<?php

use App\Actions\Admin\PartOption\CreatePartOptionAction;
use App\Models\Part;
use App\Models\PartOption;
use App\Models\ProductType;

test('it creates a new option for a part', function () {
    $product = ProductType::factory()->create();
    $part = Part::factory()->create([
        'product_type_id' => $product->id,
    ]);

    $optionData = [
        'name' => 'Test Option',
        'description' => 'Test Description',
        'base_price' => 50.00,
        'display_order' => 1,
        'in_stock' => true,
        'active' => true,
    ];

    $expectedData = array_merge($optionData, ['part_id' => $part->id]);

    $action = new CreatePartOptionAction();

    $result = $action->handle($part, $optionData);

    expect($result)->toBeArray()
        ->toHaveKeys(['message', 'option'])
        ->and($result['message'])->toBe('Option added successfully.')
        ->and($result['option']['name'])->toBe($optionData['name']);

    $storedOption = PartOption::where('name', $optionData['name'])->first();
    expect($storedOption)->not->toBeNull()
        ->and($storedOption->part_id)->toBe($part->id)
        ->and($storedOption->description)->toBe($optionData['description'])
        ->and($storedOption->base_price)->toBe(number_format($optionData['base_price'],2))
        ->and($storedOption->display_order)->toBe($optionData['display_order'])
        ->and($storedOption->in_stock)->toBe($optionData['in_stock'])
        ->and($storedOption->active)->toBe($optionData['active']);
});