<?php

namespace App\Actions\Admin\Product;

use App\Models\ProductType;

class CreateProductAction
{
    /**
     * Handle creating a new product.
     *
     * @param array $data The validated product data
     * @return array The created product and success message
     */
    public function handle(array $data): array
    {
        $product = ProductType::create($data);
        
        return [
            'message' => 'Product created successfully.',
            'product' => $product
        ];
    }
}