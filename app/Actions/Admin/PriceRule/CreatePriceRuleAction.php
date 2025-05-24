<?php

namespace App\Actions\Admin\PriceRule;

use App\Models\ProductType;

class CreatePriceRuleAction
{
    /**
     * Handle creating a new price rule.
     *
     * @param ProductType $product The product to add the price rule to
     * @param array $data The validated price rule data
     * @return array The created price rule and success message
     */
    public function handle(ProductType $product, array $data): array
    {
        $data['product_type_id'] = $product->id;
        $priceRule = $product->priceRules()->create($data);
        
        return [
            'message' => 'Price rule added successfully.',
            'priceRule' => $priceRule
        ];
    }
}