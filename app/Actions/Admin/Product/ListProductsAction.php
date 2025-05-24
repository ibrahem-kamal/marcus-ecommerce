<?php

namespace App\Actions\Admin\Product;

use App\Models\ProductType;
use Illuminate\Database\Eloquent\Collection;

class ListProductsAction
{
    /**
     * Handle retrieving all products.
     *
     * @return array The products
     */
    public function handle(): array
    {
        $products = ProductType::query()->withCount('parts')->get();
        
        return [
            'products' => $products
        ];
    }
}