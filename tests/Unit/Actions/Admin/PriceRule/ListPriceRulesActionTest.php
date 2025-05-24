<?php

use App\Actions\Admin\PriceRule\ListPriceRulesAction;
use App\Models\Part;
use App\Models\PartOption;
use App\Models\PriceRule;
use App\Models\ProductType;

test('it lists price rules for a product', function () {
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

    $action = new ListPriceRulesAction();

    $result = $action->handle($product);

    expect($result)->toBeArray()
        ->toHaveKey('priceRules')
        ->and($result['priceRules'])->toHaveCount(1)
        ->and($result['priceRules'][0]->id)->toBe($priceRule->id);

    expect($result['priceRules'][0]->relationLoaded('primaryOption'))->toBeTrue()
        ->and($result['priceRules'][0]->relationLoaded('dependentOption'))->toBeTrue()
        ->and($result['priceRules'][0]->primaryOption->relationLoaded('part'))->toBeTrue()
        ->and($result['priceRules'][0]->dependentOption->relationLoaded('part'))->toBeTrue();
});