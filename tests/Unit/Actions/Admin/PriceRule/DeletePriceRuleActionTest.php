<?php

use App\Actions\Admin\PriceRule\DeletePriceRuleAction;
use App\Models\Part;
use App\Models\PartOption;
use App\Models\PriceRule;
use App\Models\ProductType;

test('it deletes a price rule', function () {
    $product = ProductType::factory()->create();
    
    $part1 = Part::factory()->create(['product_type_id' => $product->id]);
    $part2 = Part::factory()->create(['product_type_id' => $product->id]);
    
    $option1 = PartOption::factory()->create(['part_id' => $part1->id]);
    $option2 = PartOption::factory()->create(['part_id' => $part2->id]);
    
    $priceRule = PriceRule::create([
        'product_type_id' => $product->id,
        'primary_option_id' => $option1->id,
        'dependent_option_id' => $option2->id,
        'price_adjustment' => 25.00,
        'adjustment_type' => 'fixed',
        'description' => 'Test Price Rule',
        'active' => true,
    ]);

    $action = new DeletePriceRuleAction();

    $result = $action->handle($priceRule);

    expect($result)->toBeArray()
        ->toHaveKey('message')
        ->and($result['message'])->toBe('Price rule deleted successfully.');

    expect(PriceRule::find($priceRule->id))->toBeNull();
});