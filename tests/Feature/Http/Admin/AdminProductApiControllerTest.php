<?php

use App\Models\Admin;
use App\Models\Part;
use App\Models\PartOption;
use App\Models\ProductType;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\Sanctum;

beforeEach(function () {
    $admin = Admin::factory()->create();
    Sanctum::actingAs($admin, ['*'], 'admin');
});

test('it lists all products', function () {
    ProductType::factory()->count(3)->create();

    $response = $this->getJson(route('admin.products.index'));

    $response->assertStatus(200)
        ->assertJsonStructure([
            'products'
        ])
        ->assertJsonCount(3, 'products');
});

test('it creates a new product', function () {
    $newFile = UploadedFile::fake()->image('updated.jpg');
    $productData = [
        'name' => 'Test Product',
        'description' => 'Test Description',
        'active' => true,
        'image' => $newFile, 
    ];

    $response = $this->postJson(route('admin.products.store'), $productData);

    $response->assertStatus(201)
        ->assertJsonStructure([
            'message',
            'product'
        ])
        ->assertJson([
            'message' => 'Product created successfully.',
            'product' => [
                'name' => 'Test Product',
                'description' => 'Test Description',
                'active' => 1,
            ]
        ]);

    $this->assertDatabaseHas('product_types', [
        'name' => 'Test Product',
        'description' => 'Test Description',
    ]);
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

    $response = $this->postJson(route('admin.products.store'), $productData);

    $response->assertStatus(201)
        ->assertJsonStructure([
            'message',
            'product'
        ])
        ->assertJson([
            'message' => 'Product created successfully.',
            'product' => [
                'name' => 'Test Product with Image',
                'description' => 'Test Description',
                'active' => 1,
            ]
        ]);

    $product = ProductType::where('name', 'Test Product with Image')->first();
    expect($product)->not->toBeNull();
    expect($product->image_path)->not->toBeNull();

    // Check that the file was stored
    $imagePath = str_replace('/storage/', '', $product->image_path);
    Storage::disk('public')->assertExists($imagePath);
});

test('it shows a specific product', function () {
    $product = ProductType::factory()->create();

    $response = $this->getJson(route('admin.products.show', $product));

    $response->assertStatus(200)
        ->assertJsonStructure([
            'product'
        ])
        ->assertJson([
            'product' => [
                'id' => $product->id,
                'name' => $product->name,
            ]
        ]);
});

test('it updates a product', function () {
    $product = ProductType::factory()->create();

    $updateData = [
        'name' => 'Updated Product',
        'description' => 'Updated Description',
        'active' => false,
    ];

    $response = $this->putJson(route('admin.products.update', $product), $updateData);

    $response->assertStatus(200)
        ->assertJsonStructure([
            'message',
            'product'
        ])
        ->assertJson([
            'message' => 'Product updated successfully.',
            'product' => [
                'id' => $product->id,
                'name' => 'Updated Product',
                'description' => 'Updated Description',
            ]
        ]);

    $this->assertDatabaseHas('product_types', [
        'id' => $product->id,
        'name' => 'Updated Product',
        'description' => 'Updated Description',
    ]);
});

test('it updates a product with a new image', function () {
    Storage::fake('public');

    // Create a product with an initial image
    $initialFile = UploadedFile::fake()->image('initial.jpg');
    $initialImagePath = $initialFile->store('products', 'public');
    $product = ProductType::factory()->create([
        'image_path' => Storage::url($initialImagePath)
    ]);

    // Update with a new image
    $newFile = UploadedFile::fake()->image('updated.jpg');
    $updateData = [
        'name' => 'Updated Product with Image',
        'description' => 'Updated Description',
        'active' => false,
        'image' => $newFile,
    ];

    $response = $this->postJson(route('admin.products.update', $product), array_merge($updateData, ['_method' => 'PUT']));

    $response->assertStatus(200)
        ->assertJsonStructure([
            'message',
            'product'
        ])
        ->assertJson([
            'message' => 'Product updated successfully.',
            'product' => [
                'id' => $product->id,
                'name' => 'Updated Product with Image',
                'description' => 'Updated Description',
            ]
        ]);

    $updatedProduct = ProductType::find($product->id);
    expect($updatedProduct->image_path)->not->toBeNull();
    expect($updatedProduct->image_path)->not->toBe(Storage::url($initialImagePath));

    // Check that the old file was deleted and the new file was stored
    $oldImagePath = str_replace('/storage/', '', Storage::url($initialImagePath));
    $newImagePath = str_replace('/storage/', '', $updatedProduct->image_path);

    Storage::disk('public')->assertMissing($oldImagePath);
    Storage::disk('public')->assertExists($newImagePath);
});

test('it deletes a product', function () {
    $product = ProductType::factory()->create();

    $response = $this->deleteJson(route('admin.products.destroy', $product));

    $response->assertStatus(200)
        ->assertJsonStructure([
            'message'
        ])
        ->assertJson([
            'message' => 'Product deleted successfully.'
        ]);

    $this->assertDatabaseMissing('product_types', [
        'id' => $product->id,
    ]);
});

test('it validates image upload requirements', function () {
    // Test with an invalid file type
    $invalidFile = UploadedFile::fake()->create('document.pdf', 100);

    $productData = [
        'name' => 'Test Product',
        'description' => 'Test Description',
        'active' => true,
        'image' => $invalidFile,
    ];

    $response = $this->postJson(route('admin.products.store'), $productData);
    $response->assertStatus(422)
        ->assertJsonValidationErrors(['image']);

    // Test with a file that's too large (over 2MB)
    $largeFile = UploadedFile::fake()->image('large.jpg')->size(3000); // 3MB

    $productData['image'] = $largeFile;

    $response = $this->postJson(route('admin.products.store'), $productData);
    $response->assertStatus(422)
        ->assertJsonValidationErrors(['image']);
});

test('it requires authentication for all endpoints', function () {
    auth()->forgetGuards();
    $product = ProductType::factory()->create();

    $this->getJson(route('admin.products.index'))->assertStatus(401);
    $this->postJson(route('admin.products.store'), [])->assertStatus(401);
    $this->getJson(route('admin.products.show', $product))->assertStatus(401);
    $this->putJson(route('admin.products.update', $product), [])->assertStatus(401);
    $this->deleteJson(route('admin.products.destroy', $product))->assertStatus(401);
});
