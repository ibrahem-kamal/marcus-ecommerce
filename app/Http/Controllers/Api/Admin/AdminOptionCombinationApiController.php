<?php

namespace App\Http\Controllers\Api\Admin;

use App\Actions\Admin\OptionCombination\CreateOptionCombinationAction;
use App\Actions\Admin\OptionCombination\DeleteOptionCombinationAction;
use App\Actions\Admin\OptionCombination\ListOptionCombinationsAction;
use App\Actions\Admin\OptionCombination\UpdateOptionCombinationAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\OptionCombination\CreateOptionCombinationRequest;
use App\Http\Requests\Admin\OptionCombination\UpdateOptionCombinationRequest;
use App\Models\OptionCombination;
use App\Models\ProductType;

class AdminOptionCombinationApiController extends Controller
{
    /**
     * Get option combinations for a product.
     */
    public function index(ProductType $product, ListOptionCombinationsAction $action)
    {
        $result = $action->handle($product);

        return response()->json($result);
    }

    /**
     * Store a newly created option combination in storage.
     */
    public function store(CreateOptionCombinationRequest $request, ProductType $product, CreateOptionCombinationAction $action)
    {
        $result = $action->handle($product, $request->validated());

        return response()->json($result, 201);
    }

    /**
     * Update the specified option combination in storage.
     */
    public function update(UpdateOptionCombinationRequest $request, OptionCombination $combination, UpdateOptionCombinationAction $action)
    {
        $result = $action->handle($combination, $request->validated());

        return response()->json($result);
    }

    /**
     * Remove the specified option combination from storage.
     */
    public function destroy(OptionCombination $combination, DeleteOptionCombinationAction $action)
    {
        $result = $action->handle($combination);

        return response()->json($result);
    }
}
