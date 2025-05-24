<?php

namespace App\Actions\Admin\Part;

use App\Models\Part;

class GetPartAction
{
    /**
     * Handle retrieving a specific part.
     *
     * @param Part $part The part to retrieve
     * @return array The part and its product
     */
    public function handle(Part $part): array
    {
        $part->load('options', 'productType');
        
        return [
            'part' => $part,
            'product' => $part->productType
        ];
    }
}