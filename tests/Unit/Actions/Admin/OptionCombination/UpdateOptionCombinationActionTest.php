<?php

use App\Actions\Admin\OptionCombination\UpdateOptionCombinationAction;
use App\Models\OptionCombination;
use App\Models\Part;
use App\Models\PartOption;
use App\Models\ProductType;

test('it updates an option combination', function () {
    $product = ProductType::factory()->create();
    
    $part1 = Part::factory()->create(['product_type_id' => $product->id]);
    $part2 = Part::factory()->create(['product_type_id' => $product->id]);
    $part3 = Part::factory()->create(['product_type_id' => $product->id]);
    
    $option1 = PartOption::factory()->create(['part_id' => $part1->id]);
    $option2 = PartOption::factory()->create(['part_id' => $part2->id]);
    $option3 = PartOption::factory()->create(['part_id' => $part3->id]);
    
    $combination = OptionCombination::create([
        'product_type_id' => $product->id,
        'if_option_id' => $option1->id,
        'then_part_id' => $part2->id,
        'then_option_id' => $option2->id,
        'rule_type' => 'required',
        'description' => 'Original Combination',
        'active' => true,
    ]);

    $updateData = [
        'if_option_id' => $option1->id,
        'then_part_id' => $part3->id,
        'then_option_id' => $option3->id,
        'rule_type' => 'prohibited',
        'description' => 'Updated Combination',
        'active' => false,
    ];

    $action = new UpdateOptionCombinationAction();

    $result = $action->handle($combination, $updateData);

    expect($result)->toBeArray()
        ->toHaveKeys(['message', 'combination'])
        ->and($result['message'])->toBe('Option combination updated successfully.')
        ->and($result['combination']->if_option_id)->toBe($updateData['if_option_id'])
        ->and($result['combination']->then_part_id)->toBe($updateData['then_part_id'])
        ->and($result['combination']->then_option_id)->toBe($updateData['then_option_id']);

    $combination->refresh();
    expect($combination->if_option_id)->toBe($updateData['if_option_id'])
        ->and($combination->then_part_id)->toBe($updateData['then_part_id'])
        ->and($combination->then_option_id)->toBe($updateData['then_option_id'])
        ->and($combination->rule_type)->toBe($updateData['rule_type'])
        ->and($combination->description)->toBe($updateData['description'])
        ->and($combination->active)->toBe($updateData['active']);
});