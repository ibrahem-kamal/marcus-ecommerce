<?php

namespace App\Http\Controllers\Api\Admin;

use App\Actions\Admin\PriceRule\CreatePriceRuleAction;
use App\Actions\Admin\PriceRule\DeletePriceRuleAction;
use App\Actions\Admin\PriceRule\ListPriceRulesAction;
use App\Actions\Admin\PriceRule\UpdatePriceRuleAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PriceRule\CreatePriceRuleRequest;
use App\Http\Requests\Admin\PriceRule\UpdatePriceRuleRequest;
use App\Models\PriceRule;
use App\Models\ProductType;

class AdminPriceRuleApiController extends Controller
{
    /**
     * Get price rules for a product.
     */
    public function index(ProductType $product, ListPriceRulesAction $action)
    {
        $result = $action->handle($product);

        return response()->json($result);
    }

    /**
     * Store a newly created price rule in storage.
     */
    public function store(CreatePriceRuleRequest $request, ProductType $product, CreatePriceRuleAction $action)
    {
        $result = $action->handle($product, $request->validated());

        return response()->json($result, 201);
    }

    /**
     * Update the specified price rule in storage.
     */
    public function update(UpdatePriceRuleRequest $request, PriceRule $priceRule, UpdatePriceRuleAction $action)
    {
        $result = $action->handle($priceRule, $request->validated());

        return response()->json($result);
    }

    /**
     * Remove the specified price rule from storage.
     */
    public function destroy(PriceRule $priceRule, DeletePriceRuleAction $action)
    {
        $result = $action->handle($priceRule);

        return response()->json($result);
    }
}
