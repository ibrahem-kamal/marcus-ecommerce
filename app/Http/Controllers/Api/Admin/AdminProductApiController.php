<?php

namespace App\Http\Controllers\Api\Admin;

use App\Actions\Admin\Part\CreatePartAction;
use App\Actions\Admin\Product\CreateProductAction;
use App\Actions\Admin\Product\DeleteProductAction;
use App\Actions\Admin\Product\GetProductAction;
use App\Actions\Admin\Product\ListProductsAction;
use App\Actions\Admin\Product\UpdateProductAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Part\CreatePartRequest;
use App\Http\Requests\Admin\Product\CreateProductRequest;
use App\Http\Requests\Admin\Product\UpdateProductRequest;
use App\Models\Part;
use App\Models\PartOption;
use App\Models\ProductType;
use Illuminate\Http\Request;

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

    /**
     * Store a newly created part in storage.
     */
    public function storePart(CreatePartRequest $request, ProductType $product, CreatePartAction $action)
    {
        $result = $action->handle($product, $request->validated());

        return response()->json($result, 201);
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

    /**
     * Display the specified part.
     */
    public function showPart(Part $part)
    {
        $part->load('options', 'productType');

        return response()->json([
            'part' => $part,
            'product' => $part->productType
        ]);
    }

    /**
     * Update the specified part in storage.
     */
    public function updatePart(Request $request, Part $part)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'display_order' => 'integer',
            'required' => 'boolean',
            'active' => 'boolean',
        ]);

        $part->update($validated);

        return response()->json([
            'message' => 'Part updated successfully.',
            'part' => $part
        ]);
    }

    /**
     * Display the specified option.
     */
    public function showOption(PartOption $option)
    {
        $option->load('part.productType');

        return response()->json([
            'option' => $option,
            'part' => $option->part,
            'product' => $option->part->productType
        ]);
    }

    /**
     * Update the specified option in storage.
     */
    public function updateOption(Request $request, PartOption $option)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'base_price' => 'required|numeric|min:0',
            'display_order' => 'integer',
            'in_stock' => 'boolean',
            'active' => 'boolean',
        ]);

        $option->update($validated);

        return response()->json([
            'message' => 'Option updated successfully.',
            'option' => $option
        ]);
    }

    /**
     * Get option combinations for a product.
     */
    public function getOptionCombinations(ProductType $product)
    {
        $combinations = $product->optionCombinations()
            ->with(['ifOption.part', 'thenOption.part', 'thenPart'])
            ->get();

        return response()->json([
            'combinations' => $combinations
        ]);
    }

    /**
     * Store a newly created option combination in storage.
     */
    public function storeOptionCombination(Request $request, ProductType $product)
    {
        $validated = $request->validate([
            'if_option_id' => 'required|exists:part_options,id',
            'then_part_id' => 'required|exists:parts,id',
            'then_option_id' => 'required|exists:part_options,id',
            'rule_type' => 'required|string',
            'description' => 'nullable|string',
            'active' => 'boolean',
        ]);

        $validated['product_type_id'] = $product->id;
        $combination = $product->optionCombinations()->create($validated);

        return response()->json([
            'message' => 'Option combination added successfully.',
            'combination' => $combination
        ], 201);
    }

    /**
     * Update the specified option combination in storage.
     */
    public function updateOptionCombination(Request $request, $combinationId)
    {
        $combination = \App\Models\OptionCombination::findOrFail($combinationId);

        $validated = $request->validate([
            'if_option_id' => 'required|exists:part_options,id',
            'then_part_id' => 'required|exists:parts,id',
            'then_option_id' => 'required|exists:part_options,id',
            'rule_type' => 'required|string',
            'description' => 'nullable|string',
            'active' => 'boolean',
        ]);

        $combination->update($validated);

        return response()->json([
            'message' => 'Option combination updated successfully.',
            'combination' => $combination
        ]);
    }

    /**
     * Remove the specified option combination from storage.
     */
    public function destroyOptionCombination($combinationId)
    {
        $combination = \App\Models\OptionCombination::findOrFail($combinationId);
        $combination->delete();

        return response()->json([
            'message' => 'Option combination deleted successfully.'
        ]);
    }

    /**
     * Get price rules for a product.
     */
    public function getPriceRules(ProductType $product)
    {
        $priceRules = $product->priceRules()
            ->with(['primaryOption.part', 'dependentOption.part'])
            ->get();

        return response()->json([
            'priceRules' => $priceRules
        ]);
    }

    /**
     * Store a newly created price rule in storage.
     */
    public function storePriceRule(Request $request, ProductType $product)
    {
        $validated = $request->validate([
            'primary_option_id' => 'required|exists:part_options,id',
            'dependent_option_id' => 'required|exists:part_options,id',
            'price_adjustment' => 'required|numeric',
            'adjustment_type' => 'required|string',
            'description' => 'nullable|string',
            'active' => 'boolean',
        ]);

        $validated['product_type_id'] = $product->id;
        $priceRule = $product->priceRules()->create($validated);

        return response()->json([
            'message' => 'Price rule added successfully.',
            'priceRule' => $priceRule
        ], 201);
    }

    /**
     * Update the specified price rule in storage.
     */
    public function updatePriceRule(Request $request, $priceRuleId)
    {
        $priceRule = \App\Models\PriceRule::findOrFail($priceRuleId);

        $validated = $request->validate([
            'primary_option_id' => 'required|exists:part_options,id',
            'dependent_option_id' => 'required|exists:part_options,id',
            'price_adjustment' => 'required|numeric',
            'adjustment_type' => 'required|string',
            'description' => 'nullable|string',
            'active' => 'boolean',
        ]);

        $priceRule->update($validated);

        return response()->json([
            'message' => 'Price rule updated successfully.',
            'priceRule' => $priceRule
        ]);
    }

    /**
     * Remove the specified price rule from storage.
     */
    public function destroyPriceRule($priceRuleId)
    {
        $priceRule = \App\Models\PriceRule::findOrFail($priceRuleId);
        $priceRule->delete();

        return response()->json([
            'message' => 'Price rule deleted successfully.'
        ]);
    }

    /**
     * Remove the specified part from storage.
     */
    public function destroyPart(Part $part)
    {
        $part->delete();

        return response()->json([
            'message' => 'Part deleted successfully.'
        ]);
    }

    /**
     * Remove the specified option from storage.
     */
    public function destroyOption(PartOption $option)
    {
        $option->delete();

        return response()->json([
            'message' => 'Option deleted successfully.'
        ]);
    }
}
