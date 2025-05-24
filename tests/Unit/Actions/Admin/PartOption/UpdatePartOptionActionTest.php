<?php

use App\Actions\Admin\PartOption\UpdatePartOptionAction;
use App\Models\Part;
use App\Models\PartOption;
use App\Models\ProductType;

test('it updates an option', function () {
    $productType = ProductType::factory()->create();
    $part = Part::factory()->create([
        'product_type_id' => $productType->id,
    ]);
    $option = PartOption::factory()->create([
        'part_id' => $part->id,
        'name' => 'Original Option',
        'description' => 'Original Description',
        'base_price' => 50.00,
        'display_order' => 1,
        'in_stock' => true,
        'active' => true,
    ]);

    $updateData = [
        'name' => 'Updated Option',
        'description' => 'Updated Description',
        'base_price' => 75.00,
        'display_order' => 2,
        'in_stock' => false,
        'active' => true,
    ];

    $action = new UpdatePartOptionAction();

    $result = $action->handle($option, $updateData);

    expect($result)->toBeArray()
        ->toHaveKeys(['message', 'option'])
        ->and($result['message'])->toBe('Option updated successfully.')
        ->and($result['option']->name)->toBe($updateData['name']);

    $option->refresh();
    expect($option->name)->toBe($updateData['name'])
        ->and($option->description)->toBe($updateData['description'])
        ->and($option->base_price)->toBe(number_format($updateData['base_price'],2))
        ->and($option->display_order)->toBe($updateData['display_order'])
        ->and($option->in_stock)->toBe($updateData['in_stock'])
        ->and($option->active)->toBe($updateData['active']);
});