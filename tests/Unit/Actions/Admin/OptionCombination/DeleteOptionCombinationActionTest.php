<?php

use App\Actions\Admin\OptionCombination\DeleteOptionCombinationAction;
use App\Models\OptionCombination;
use App\Models\Part;
use App\Models\PartOption;
use App\Models\ProductType;

test('it deletes an option combination', function () {
    $product = ProductType::factory()->create();
    
    $part1 = Part::factory()->create(['product_type_id' => $product->id]);
    $part2 = Part::factory()->create(['product_type_id' => $product->id]);
    
    $option1 = PartOption::factory()->create(['part_id' => $part1->id]);
    $option2 = PartOption::factory()->create(['part_id' => $part2->id]);
    
    $combination = OptionCombination::create([
        'product_type_id' => $product->id,
        'if_option_id' => $option1->id,
        'then_part_id' => $part2->id,
        'then_option_id' => $option2->id,
        'rule_type' => 'required',
        'description' => 'Test Combination',
        'active' => true,
    ]);

    $action = new DeleteOptionCombinationAction();

    $result = $action->handle($combination);

    expect($result)->toBeArray()
        ->toHaveKey('message')
        ->and($result['message'])->toBe('Option combination deleted successfully.');

    expect(OptionCombination::find($combination->id))->toBeNull();
});