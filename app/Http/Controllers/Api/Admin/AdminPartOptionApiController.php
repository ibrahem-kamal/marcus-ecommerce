<?php

namespace App\Http\Controllers\Api\Admin;

use App\Actions\Admin\PartOption\CreatePartOptionAction;
use App\Actions\Admin\PartOption\DeletePartOptionAction;
use App\Actions\Admin\PartOption\GetPartOptionAction;
use App\Actions\Admin\PartOption\UpdatePartOptionAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PartOption\CreatePartOptionRequest;
use App\Http\Requests\Admin\PartOption\UpdatePartOptionRequest;
use App\Models\Part;
use App\Models\PartOption;

class AdminPartOptionApiController extends Controller
{
    /**
     * Store a newly created option in storage.
     */
    public function store(CreatePartOptionRequest $request, Part $part, CreatePartOptionAction $action)
    {
        $result = $action->handle($part, $request->validated());

        return response()->json($result, 201);
    }

    /**
     * Display the specified option.
     */
    public function show(PartOption $option, GetPartOptionAction $action)
    {
        $result = $action->handle($option);

        return response()->json($result);
    }

    /**
     * Update the specified option in storage.
     */
    public function update(UpdatePartOptionRequest $request, PartOption $option, UpdatePartOptionAction $action)
    {
        $result = $action->handle($option, $request->validated());

        return response()->json($result);
    }

    /**
     * Remove the specified option from storage.
     */
    public function destroy(PartOption $option, DeletePartOptionAction $action)
    {
        $result = $action->handle($option);

        return response()->json($result);
    }
}
