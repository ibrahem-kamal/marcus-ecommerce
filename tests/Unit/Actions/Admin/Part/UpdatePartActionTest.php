<?php

use App\Actions\Admin\Part\UpdatePartAction;
use App\Models\Part;
use App\Models\ProductType;

test('it updates a part', function () {
    $productType = ProductType::factory()->create();

    $part = Part::factory()->create([
        'product_type_id' => $productType->id,
        'name' => 'Test Part',
        'description' => 'Test Description',
        'display_order' => 1,
        'required' => true,
        'active' => true,
    ]);

    $partData = [
        'name' => 'Updated Part',
        'description' => 'Updated Description',
        'display_order' => 2,
        'required' => false,
        'active' => true,
    ];

    $action = new UpdatePartAction();

    $result = $action->handle($part, $partData);

    expect($result)->toBeArray()
        ->toHaveKeys(['message', 'part'])
        ->and($result['message'])->toBe('Part updated successfully.')
        ->and($result['part']->id)->toBe($part->id);

    $updatedPart = Part::find($part->id);
    expect($updatedPart->name)->toBe($partData['name'])
        ->and($updatedPart->description)->toBe($partData['description'])
        ->and($updatedPart->display_order)->toBe($partData['display_order'])
        ->and($updatedPart->required)->toBe($partData['required'])
        ->and($updatedPart->active)->toBe($partData['active']);
});
