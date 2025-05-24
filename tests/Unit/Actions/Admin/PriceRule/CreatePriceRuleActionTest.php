<?php

use App\Actions\Admin\PriceRule\CreatePriceRuleAction;
use App\Models\Part;
use App\Models\PartOption;
use App\Models\PriceRule;
use App\Models\ProductType;

test('it creates a new price rule for a product', function () {
    $product = ProductType::factory()->create();
    
    $part1 = Part::factory()->create(['product_type_id' => $product->id]);
    $part2 = Part::factory()->create(['product_type_id' => $product->id]);
    
    $option1 = PartOption::factory()->create(['part_id' => $part1->id]);
    $option2 = PartOption::factory()->create(['part_id' => $part2->id]);
    
    $priceRuleData = [
        'primary_option_id' => $option1->id,
        'dependent_option_id' => $option2->id,
        'price_adjustment' => 25.00,
        'adjustment_type' => 'fixed',
        'description' => 'Test Price Rule',
        'active' => true,
    ];

    $action = new CreatePriceRuleAction();

    $result = $action->handle($product, $priceRuleData);

    expect($result)->toBeArray()
        ->toHaveKeys(['message', 'priceRule'])
        ->and($result['message'])->toBe('Price rule added successfully.')
        ->and($result['priceRule']->primary_option_id)->toBe($option1->id)
        ->and($result['priceRule']->dependent_option_id)->toBe($option2->id);

    $storedPriceRule = PriceRule::where('primary_option_id', $option1->id)
        ->where('dependent_option_id', $option2->id)
        ->first();
        
    expect($storedPriceRule)->not->toBeNull()
        ->and($storedPriceRule->product_type_id)->toBe($product->id)
        ->and($storedPriceRule->price_adjustment)->toBe(number_format($priceRuleData['price_adjustment'],2))
        ->and($storedPriceRule->adjustment_type)->toBe($priceRuleData['adjustment_type'])
        ->and($storedPriceRule->description)->toBe($priceRuleData['description'])
        ->and($storedPriceRule->active)->toBe($priceRuleData['active']);
});