<?php

namespace App\Actions\Admin\OptionCombination;

use App\Models\ProductType;

class CreateOptionCombinationAction
{
    /**
     * Handle creating a new option combination.
     *
     * @param ProductType $product The product to add the combination to
     * @param array $data The validated combination data
     * @return array The created combination and success message
     */
    public function handle(ProductType $product, array $data): array
    {
        $data['product_type_id'] = $product->id;
        $combination = $product->optionCombinations()->create($data);
        
        return [
            'message' => 'Option combination added successfully.',
            'combination' => $combination
        ];
    }
}