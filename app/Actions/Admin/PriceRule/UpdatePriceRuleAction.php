<?php

namespace App\Actions\Admin\PriceRule;

use App\Models\PriceRule;

class UpdatePriceRuleAction
{
    /**
     * Handle updating a price rule.
     *
     * @param PriceRule $priceRule The price rule to update
     * @param array $data The validated price rule data
     * @return array The updated price rule and success message
     */
    public function handle(PriceRule $priceRule, array $data): array
    {
        $priceRule->update($data);
        
        return [
            'message' => 'Price rule updated successfully.',
            'priceRule' => $priceRule
        ];
    }
}