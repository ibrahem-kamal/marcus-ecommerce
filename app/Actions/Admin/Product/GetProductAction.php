<?php

namespace App\Actions\Admin\Product;

use App\Models\ProductType;

class GetProductAction
{
    /**
     * Handle retrieving a specific product.
     *
     * @param ProductType $product The product to retrieve
     * @return array The product
     */
    public function handle(ProductType $product): array
    {
        $product->load('parts.options');
        
        return [
            'product' => $product
        ];
    }
}