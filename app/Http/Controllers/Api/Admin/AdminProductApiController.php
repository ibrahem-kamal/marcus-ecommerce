<?php

namespace App\Http\Controllers\Api\Admin;

use App\Actions\Admin\Product\CreateProductAction;
use App\Actions\Admin\Product\DeleteProductAction;
use App\Actions\Admin\Product\GetProductAction;
use App\Actions\Admin\Product\ListProductsAction;
use App\Actions\Admin\Product\UpdateProductAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Product\CreateProductRequest;
use App\Http\Requests\Admin\Product\UpdateProductRequest;
use App\Models\ProductType;

class AdminProductApiController extends Controller
{
    /**
     * Display a listing of the products.
     */
    public function index(ListProductsAction $action)
    {
        $result = $action->handle();

        return response()->json($result);
    }

    /**
     * Store a newly created product in storage.
     */
    public function store(CreateProductRequest $request, CreateProductAction $action)
    {
        $result = $action->handle($request->validated());

        return response()->json($result, 201);
    }

    /**
     * Display the specified product.
     */
    public function show(ProductType $product, GetProductAction $action)
    {
        $result = $action->handle($product);

        return response()->json($result);
    }

    /**
     * Update the specified product in storage.
     */
    public function update(UpdateProductRequest $request, ProductType $product, UpdateProductAction $action)
    {
        $result = $action->handle($product, $request->validated());

        return response()->json($result);
    }

    /**
     * Remove the specified product from storage.
     */
    public function destroy(ProductType $product, DeleteProductAction $action)
    {
        $result = $action->handle($product);

        return response()->json($result);
    }
}
