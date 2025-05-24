<?php

use App\Actions\Admin\Product\ListProductsAction;
use App\Models\Part;
use App\Models\ProductType;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

test('it retrieves all products with parts count', function () {
    $products = ProductType::factory()->count(5)->create();
    $productPart = Part::factory(['product_type_id' => $products->first()->id])->count(5)->create();
    $action = new ListProductsAction();

    $result = $action->handle();

    expect($result)->toBeArray()
        ->toHaveKey('products')
        ->and(count($result['products']))->toBe($products->count())
        ->and($result['products'][0])->toBeInstanceOf(ProductType::class)
        ->and($result['products'][0]['parts_count'])->toEqual(5);
});
