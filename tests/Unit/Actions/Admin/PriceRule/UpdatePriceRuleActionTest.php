<?php

use App\Actions\Admin\PriceRule\UpdatePriceRuleAction;
use App\Models\Part;
use App\Models\PartOption;
use App\Models\PriceRule;
use App\Models\ProductType;

test('it updates a price rule', function () {
    $product = ProductType::factory()->create();
    
    $part1 = Part::factory()->create(['product_type_id' => $product->id]);
    $part2 = Part::factory()->create(['product_type_id' => $product->id]);
    $part3 = Part::factory()->create(['product_type_id' => $product->id]);
    
    $option1 = PartOption::factory()->create(['part_id' => $part1->id]);
    $option2 = PartOption::factory()->create(['part_id' => $part2->id]);
    $option3 = PartOption::factory()->create(['part_id' => $part3->id]);
    
    $priceRule = PriceRule::create([
        'product_type_id' => $product->id,
        'primary_option_id' => $option1->id,
        'dependent_option_id' => $option2->id,
        'price_adjustment' => 25.00,
        'adjustment_type' => 'fixed',
        'description' => 'Original Price Rule',
        'active' => true,
    ]);

    $updateData = [
        'primary_option_id' => $option1->id,
        'dependent_option_id' => $option3->id,
        'price_adjustment' => 50.00,
        'adjustment_type' => 'percentage',
        'description' => 'Updated Price Rule',
        'active' => false,
    ];

    $action = new UpdatePriceRuleAction();

    $result = $action->handle($priceRule, $updateData);

    expect($result)->toBeArray()
        ->toHaveKeys(['message', 'priceRule'])
        ->and($result['message'])->toBe('Price rule updated successfully.')
        ->and($result['priceRule']->primary_option_id)->toBe($updateData['primary_option_id'])
        ->and($result['priceRule']->dependent_option_id)->toBe($updateData['dependent_option_id']);

    $priceRule->refresh();
    expect($priceRule->primary_option_id)->toBe($updateData['primary_option_id'])
        ->and($priceRule->dependent_option_id)->toBe($updateData['dependent_option_id'])
        ->and($priceRule->price_adjustment)->toBe(number_format($updateData['price_adjustment'],2))
        ->and($priceRule->adjustment_type)->toBe($updateData['adjustment_type'])
        ->and($priceRule->description)->toBe($updateData['description'])
        ->and($priceRule->active)->toBe($updateData['active']);
});