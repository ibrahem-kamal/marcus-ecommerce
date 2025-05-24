<?php

use App\Actions\Admin\PartOption\DeletePartOptionAction;
use App\Models\Part;
use App\Models\PartOption;
use App\Models\ProductType;

test('it deletes an option', function () {
    $productType = ProductType::factory()->create();
    $part = Part::factory()->create([
        'product_type_id' => $productType->id,
    ]);
    $option = PartOption::factory()->create([
        'part_id' => $part->id,
        'name' => 'Test Option',
        'description' => 'Test Description',
    ]);

    $action = new DeletePartOptionAction();

    $result = $action->handle($option);

    expect($result)->toBeArray()
        ->toHaveKey('message')
        ->and($result['message'])->toBe('Option deleted successfully.');

    expect(PartOption::find($option->id))->toBeNull();
});