<?php

use App\Actions\Admin\Product\CreateProductAction;
use App\Models\ProductType;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

test('it creates a new product', function () {
    $productData = [
        'name' => 'Test Product',
        'description' => 'Test Description',
        'active' => true,
    ];

    $createdProduct = ProductType::factory()->make($productData);

    $action = new CreateProductAction();

    $result = $action->handle($productData);
    $storedProduct = ProductType::where('name', $productData['name'])->first();

    expect($result)->toBeArray()
        ->toHaveKeys(['message', 'product'])
        ->and($result['message'])->toBe('Product created successfully.')
        ->and($result['product']['name'])->toBe($createdProduct->name);

    expect($storedProduct)->not->toBeNull()
        ->and($storedProduct->name)->toBe($createdProduct->name)
        ->and($storedProduct->description)->toBe($createdProduct->description)
        ->and($storedProduct->active)->toBe($createdProduct->active);
});

test('it creates a new product with an image', function () {
    Storage::fake('public');

    $file = UploadedFile::fake()->image('product.jpg');

    $productData = [
        'name' => 'Test Product with Image',
        'description' => 'Test Description',
        'active' => true,
        'image' => $file,
    ];

    $action = new CreateProductAction();
    $result = $action->handle($productData);

    $storedProduct = ProductType::where('name', $productData['name'])->first();

    expect($result)->toBeArray()
        ->toHaveKeys(['message', 'product'])
        ->and($result['message'])->toBe('Product created successfully.')
        ->and($result['product']['name'])->toBe($productData['name']);

    expect($storedProduct)->not->toBeNull()
        ->and($storedProduct->name)->toBe($productData['name'])
        ->and($storedProduct->image_path)->not->toBeNull();

    // Check that the file was stored
    $imagePath = str_replace('/storage/', '', $storedProduct->image_path);
    Storage::disk('public')->assertExists($imagePath);
});
