<?php

namespace App\Actions\Admin\OptionCombination;

use App\Models\ProductType;

class ListOptionCombinationsAction
{
    /**
     * Handle retrieving option combinations for a product.
     *
     * @param ProductType $product The product to retrieve combinations for
     * @return array The combinations
     */
    public function handle(ProductType $product): array
    {
        $combinations = $product->optionCombinations()
            ->with(['ifOption.part', 'thenOption.part', 'thenPart'])
            ->get();
        
        return [
            'combinations' => $combinations
        ];
    }
}