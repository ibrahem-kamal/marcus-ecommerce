<?php

use App\Actions\Admin\Part\DeletePartAction;
use App\Models\Part;
use App\Models\ProductType;

test('it deletes a part', function () {
    $productType = ProductType::factory()->create();

    $part = Part::factory()->create([
        'product_type_id' => $productType->id,
        'name' => 'Test Part',
        'description' => 'Test Description',
        'display_order' => 1,
        'required' => true,
        'active' => true,
    ]);

    $partId = $part->id;

    $action = new DeletePartAction();

    $result = $action->handle($part);

    expect($result)->toBeArray()
        ->toHaveKey('message')
        ->and($result['message'])->toBe('Part deleted successfully.');

    expect(Part::find($partId))->toBeNull();
});
