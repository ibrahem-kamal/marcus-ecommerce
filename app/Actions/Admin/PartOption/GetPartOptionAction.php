<?php

namespace App\Actions\Admin\PartOption;

use App\Models\PartOption;

class GetPartOptionAction
{
    /**
     * Handle retrieving a specific option.
     *
     * @param PartOption $option The option to retrieve
     * @return array The option, its part, and its product
     */
    public function handle(PartOption $option): array
    {
        $option->load('part.productType');
        
        return [
            'option' => $option,
            'part' => $option->part,
            'product' => $option->part->productType
        ];
    }
}