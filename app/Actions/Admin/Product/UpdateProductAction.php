<?php

namespace App\Actions\Admin\Product;

use App\Models\ProductType;

class UpdateProductAction
{
    /**
     * Handle updating a product.
     *
     * @param ProductType $product The product to update
     * @param array $data The validated product data
     * @return array The updated product and success message
     */
    public function handle(ProductType $product, array $data): array
    {
        $product->update($data);
        
        return [
            'message' => 'Product updated successfully.',
            'product' => $product
        ];
    }
}