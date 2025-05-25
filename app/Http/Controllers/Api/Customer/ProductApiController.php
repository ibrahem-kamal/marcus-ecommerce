<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Controller;
use App\Models\ProductType;
use Illuminate\Http\JsonResponse;

class ProductApiController extends Controller
{
    /**
     * Display a listing of the products.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $products = ProductType::query()
            ->where('active', true)
            ->get(['id', 'name', 'description', 'image_path']);
        
        return response()->json(['products' => $products]);
    }

    /**
     * Display the specified product with its configuration options.
     *
     * @param ProductType $product
     * @return JsonResponse
     */
    public function show(ProductType $product): JsonResponse
    {
        if (!$product->active) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        $product->load([
            'parts' => function ($query) {
                $query->active()
                    ->orderBy('display_order');
            },
            'parts.options' => function ($query) {
                $query->active()
                    ->inStock()
                    ->orderBy('display_order');
            },
            'optionCombinations' => function ($query) {
                $query->active();
            },
            'priceRules' => function ($query) {
                $query->active();
            }
        ]);
        
        return response()->json(['product' => $product]);
    }
}