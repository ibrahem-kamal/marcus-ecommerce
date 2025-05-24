<?php

use App\Actions\Admin\OptionCombination\CreateOptionCombinationAction;
use App\Models\OptionCombination;
use App\Models\Part;
use App\Models\PartOption;
use App\Models\ProductType;

test('it creates a new option combination for a product', function () {
    $product = ProductType::factory()->create();
    
    $part1 = Part::factory()->create(['product_type_id' => $product->id]);
    $part2 = Part::factory()->create(['product_type_id' => $product->id]);
    
    $option1 = PartOption::factory()->create(['part_id' => $part1->id]);
    $option2 = PartOption::factory()->create(['part_id' => $part2->id]);
    
    $combinationData = [
        'if_option_id' => $option1->id,
        'then_part_id' => $part2->id,
        'then_option_id' => $option2->id,
        'rule_type' => 'required',
        'description' => 'Test Combination',
        'active' => true,
    ];

    $action = new CreateOptionCombinationAction();

    $result = $action->handle($product, $combinationData);

    expect($result)->toBeArray()
        ->toHaveKeys(['message', 'combination'])
        ->and($result['message'])->toBe('Option combination added successfully.')
        ->and($result['combination']->if_option_id)->toBe($option1->id)
        ->and($result['combination']->then_part_id)->toBe($part2->id)
        ->and($result['combination']->then_option_id)->toBe($option2->id);

    $storedCombination = OptionCombination::where('if_option_id', $option1->id)
        ->where('then_part_id', $part2->id)
        ->where('then_option_id', $option2->id)
        ->first();
        
    expect($storedCombination)->not->toBeNull()
        ->and($storedCombination->product_type_id)->toBe($product->id)
        ->and($storedCombination->rule_type)->toBe($combinationData['rule_type'])
        ->and($storedCombination->description)->toBe($combinationData['description'])
        ->and($storedCombination->active)->toBe($combinationData['active']);
});