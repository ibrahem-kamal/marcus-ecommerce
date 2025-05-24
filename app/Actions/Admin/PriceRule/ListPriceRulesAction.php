<?php

namespace App\Actions\Admin\PriceRule;

use App\Models\ProductType;

class ListPriceRulesAction
{
    /**
     * Handle retrieving price rules for a product.
     *
     * @param ProductType $product The product to retrieve price rules for
     * @return array The price rules
     */
    public function handle(ProductType $product): array
    {
        $priceRules = $product->priceRules()
            ->with(['primaryOption.part', 'dependentOption.part'])
            ->get();
        
        return [
            'priceRules' => $priceRules
        ];
    }
}