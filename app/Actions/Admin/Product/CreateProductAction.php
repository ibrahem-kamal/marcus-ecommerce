<?php

namespace App\Actions\Admin\Product;

use App\Models\ProductType;
use Illuminate\Support\Facades\Storage;

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
        // Handle image upload if present
        if (isset($data['image']) && $data['image']) {
            $imagePath = $data['image']->store('products', 'public');
            $data['image_path'] = Storage::url($imagePath);
            unset($data['image']);
        }

        $product = ProductType::create($data);

        return [
            'message' => 'Product created successfully.',
            'product' => $product
        ];
    }
}
