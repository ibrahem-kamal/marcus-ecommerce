<?php

use App\Actions\Admin\Product\UpdateProductAction;
use App\Models\ProductType;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

test('it updates a product', function () {
    $product = ProductType::factory()->create([
        'name' => 'Test Product',
        'description' => 'Test Description',
        'active' => true,
    ]);

    $productData = [
        'name' => 'Updated Product',
        'description' => 'Updated Description',
        'active' => false,
    ];

    $action = new UpdateProductAction();

    $result = $action->handle($product, $productData);

    expect($result)->toBeArray()
        ->toHaveKeys(['message', 'product'])
        ->and($result['message'])->toBe('Product updated successfully.')
        ->and($result['product']->id)->toBe($product->id);

    $updatedProduct = ProductType::find($product->id);
    expect($updatedProduct->name)->toBe($productData['name'])
        ->and($updatedProduct->description)->toBe($productData['description'])
        ->and($updatedProduct->active)->toBe($productData['active']);
});

test('it updates a product with a new image', function () {
    Storage::fake('public');

    // Create a product with an initial image
    $initialFile = UploadedFile::fake()->image('initial.jpg');
    $initialImagePath = $initialFile->store('products', 'public');
    $product = ProductType::factory()->create([
        'name' => 'Test Product',
        'description' => 'Test Description',
        'active' => true,
        'image_path' => Storage::url($initialImagePath)
    ]);

    // Update with a new image
    $newFile = UploadedFile::fake()->image('updated.jpg');
    $productData = [
        'name' => 'Updated Product with Image',
        'description' => 'Updated Description',
        'active' => false,
        'image' => $newFile,
    ];

    $action = new UpdateProductAction();
    $result = $action->handle($product, $productData);

    expect($result)->toBeArray()
        ->toHaveKeys(['message', 'product'])
        ->and($result['message'])->toBe('Product updated successfully.')
        ->and($result['product']->id)->toBe($product->id);

    $updatedProduct = ProductType::find($product->id);
    expect($updatedProduct->name)->toBe($productData['name'])
        ->and($updatedProduct->description)->toBe($productData['description'])
        ->and($updatedProduct->active)->toBe($productData['active'])
        ->and($updatedProduct->image_path)->not->toBeNull()
        ->and($updatedProduct->image_path)->not->toBe(Storage::url($initialImagePath));

    // Check that the old file was deleted and the new file was stored
    $oldImagePath = str_replace('/storage/', '', Storage::url($initialImagePath));
    $newImagePath = str_replace('/storage/', '', $updatedProduct->image_path);

    Storage::disk('public')->assertMissing($oldImagePath);
    Storage::disk('public')->assertExists($newImagePath);
});
