<?php

namespace App\Actions\Admin\Product;

use App\Models\ProductType;

class DeleteProductAction
{
    /**
     * Handle deleting a product.
     *
     * @param ProductType $product The product to delete
     * @return array The success message
     */
    public function handle(ProductType $product): array
    {
        $product->delete();
        
        return [
            'message' => 'Product deleted successfully.'
        ];
    }
}