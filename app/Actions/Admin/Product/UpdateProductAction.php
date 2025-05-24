<?php

namespace App\Actions\Admin\Product;

use App\Models\ProductType;
use Illuminate\Support\Facades\Storage;

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
        // Handle image upload if present
        if (isset($data['image']) && $data['image']) {
            // Delete old image if exists
            if ($product->image_path) {
                $oldPath = str_replace('/storage/', '', $product->image_path);
                if (Storage::disk('public')->exists($oldPath)) {
                    Storage::disk('public')->delete($oldPath);
                }
            }

            // Store new image
            $imagePath = $data['image']->store('products', 'public');
            $data['image_path'] = Storage::url($imagePath);
            unset($data['image']);
        }

        $product->update($data);

        return [
            'message' => 'Product updated successfully.',
            'product' => $product
        ];
    }
}
