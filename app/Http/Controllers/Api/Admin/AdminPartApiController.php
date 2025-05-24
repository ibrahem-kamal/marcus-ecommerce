<?php

namespace App\Http\Controllers\Api\Admin;

use App\Actions\Admin\Part\CreatePartAction;
use App\Actions\Admin\Part\DeletePartAction;
use App\Actions\Admin\Part\GetPartAction;
use App\Actions\Admin\Part\UpdatePartAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Part\CreatePartRequest;
use App\Http\Requests\Admin\Part\UpdatePartRequest;
use App\Models\Part;
use App\Models\ProductType;

class AdminPartApiController extends Controller
{
    /**
     * Store a newly created part in storage.
     */
    public function store(CreatePartRequest $request, ProductType $product, CreatePartAction $action)
    {
        $result = $action->handle($product, $request->validated());

        return response()->json($result, 201);
    }

    /**
     * Display the specified part.
     */
    public function show(Part $part, GetPartAction $action)
    {
        $result = $action->handle($part);

        return response()->json($result);
    }

    /**
     * Update the specified part in storage.
     */
    public function update(UpdatePartRequest $request, Part $part, UpdatePartAction $action)
    {
        $result = $action->handle($part, $request->validated());

        return response()->json($result);
    }

    /**
     * Remove the specified part from storage.
     */
    public function destroy(Part $part, DeletePartAction $action)
    {
        $result = $action->handle($part);

        return response()->json($result);
    }
}
