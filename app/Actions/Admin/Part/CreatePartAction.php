<?php

namespace App\Actions\Admin\Part;

use App\Models\Part;
use App\Models\ProductType;

class CreatePartAction
{
    /**
     * Handle creating a new part.
     *
     * @param ProductType $product The product to add the part to
     * @param array $data The validated part data
     * @return array The created part and success message
     */
    public function handle(ProductType $product, array $data): array
    {
        $data['product_type_id'] = $product->id;
        $part = Part::create($data);
        
        return [
            'message' => 'Part added successfully.',
            'part' => $part
        ];
    }
}