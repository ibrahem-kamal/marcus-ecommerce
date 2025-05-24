<?php

use App\Actions\Admin\OptionCombination\ListOptionCombinationsAction;
use App\Models\OptionCombination;
use App\Models\Part;
use App\Models\PartOption;
use App\Models\ProductType;

test('it lists option combinations for a product', function () {
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

    $action = new ListOptionCombinationsAction();

    $result = $action->handle($product);

    expect($result)->toBeArray()
        ->toHaveKey('combinations')
        ->and($result['combinations'])->toHaveCount(1)
        ->and($result['combinations'][0]->id)->toBe($combination->id);

    expect($result['combinations'][0]->relationLoaded('ifOption'))->toBeTrue()
        ->and($result['combinations'][0]->relationLoaded('thenOption'))->toBeTrue()
        ->and($result['combinations'][0]->relationLoaded('thenPart'))->toBeTrue()
        ->and($result['combinations'][0]->ifOption->relationLoaded('part'))->toBeTrue()
        ->and($result['combinations'][0]->thenOption->relationLoaded('part'))->toBeTrue();
});