<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Part;
use App\Models\PartOption;
use App\Models\ProductType;
use Illuminate\Http\Request;

class AdminProductApiController extends Controller
{
    /**
     * Display a listing of the products.
     */
    public function index()
    {
        $products = ProductType::withCount('parts')->get();
        
        return response()->json([
            'products' => $products
        ]);
    }

    /**
     * Store a newly created product in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'active' => 'boolean',
        ]);

        $product = ProductType::create($validated);

        return response()->json([
            'message' => 'Product created successfully.',
            'product' => $product
        ], 201);
    }

    /**
     * Display the specified product.
     */
    public function show(ProductType $product)
    {
        $product->load('parts.options');
        
        return response()->json([
            'product' => $product
        ]);
    }

    /**
     * Update the specified product in storage.
     */
    public function update(Request $request, ProductType $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'active' => 'boolean',
        ]);

        $product->update($validated);

        return response()->json([
            'message' => 'Product updated successfully.',
            'product' => $product
        ]);
    }

    /**
     * Remove the specified product from storage.
     */
    public function destroy(ProductType $product)
    {
        $product->delete();

        return response()->json([
            'message' => 'Product deleted successfully.'
        ]);
    }

    /**
     * Store a newly created part in storage.
     */
    public function storePart(Request $request, ProductType $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'display_order' => 'integer',
            'required' => 'boolean',
            'active' => 'boolean',
        ]);

        $validated['product_type_id'] = $product->id;
        $part = Part::create($validated);

        return response()->json([
            'message' => 'Part added successfully.',
            'part' => $part
        ], 201);
    }

    /**
     * Store a newly created option in storage.
     */
    public function storeOption(Request $request, Part $part)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'base_price' => 'required|numeric|min:0',
            'display_order' => 'integer',
            'in_stock' => 'boolean',
            'active' => 'boolean',
        ]);

        $validated['part_id'] = $part->id;
        $option = PartOption::create($validated);

        return response()->json([
            'message' => 'Option added successfully.',
            'option' => $option
        ], 201);
    }
    public function getPart(Request $request, Part $part)
    {
        return response()->json([
            'part' => $part->load('productType')
        ]);
    }
}