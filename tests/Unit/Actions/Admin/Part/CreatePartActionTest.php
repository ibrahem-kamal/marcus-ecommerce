<?php

use App\Actions\Admin\Part\CreatePartAction;
use App\Models\Part;
use App\Models\ProductType;

test('it creates a new part for a product', function () {
    $product = ProductType::factory()->create();

    $partData = [
        'name' => 'Test Part',
        'description' => 'Test Description',
        'display_order' => 1,
        'required' => true,
        'active' => true,
    ];

    $expectedData = array_merge($partData, ['product_type_id' => $product->id]);

    $action = new CreatePartAction();

    $result = $action->handle($product, $partData);

    expect($result)->toBeArray()
        ->toHaveKeys(['message', 'part'])
        ->and($result['message'])->toBe('Part added successfully.')
        ->and($result['part']['name'])->toBe($partData['name']);

    $storedPart = Part::where('name', $partData['name'])->first();
    expect($storedPart)->not->toBeNull()
        ->and($storedPart->product_type_id)->toBe($product->id)
        ->and($storedPart->description)->toBe($partData['description'])
        ->and($storedPart->display_order)->toBe($partData['display_order'])
        ->and($storedPart->required)->toBe($partData['required'])
        ->and($storedPart->active)->toBe($partData['active']);
});
