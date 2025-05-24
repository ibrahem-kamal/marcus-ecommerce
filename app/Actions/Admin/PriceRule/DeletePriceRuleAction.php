<?php

namespace App\Actions\Admin\PriceRule;

use App\Models\PriceRule;

class DeletePriceRuleAction
{
    /**
     * Handle deleting a price rule.
     *
     * @param PriceRule $priceRule The price rule to delete
     * @return array The success message
     */
    public function handle(PriceRule $priceRule): array
    {
        $priceRule->delete();
        
        return [
            'message' => 'Price rule deleted successfully.'
        ];
    }
}