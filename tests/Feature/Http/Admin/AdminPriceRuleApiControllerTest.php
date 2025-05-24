<?php

use App\Models\Admin;
use App\Models\Part;
use App\Models\PartOption;
use App\Models\PriceRule;
use App\Models\ProductType;
use Laravel\Sanctum\Sanctum;

beforeEach(function () {
    $admin = Admin::factory()->create();
    Sanctum::actingAs($admin, ['*']);
});

test('it lists price rules for a product', function () {
    $product = ProductType::factory()->create();
    
    $part1 = Part::factory()->create(['product_type_id' => $product->id]);
    $part2 = Part::factory()->create(['product_type_id' => $product->id]);
    
    $option1 = PartOption::factory()->create(['part_id' => $part1->id]);
    $option2 = PartOption::factory()->create(['part_id' => $part2->id]);
    
    $priceRule = PriceRule::create([
        'product_type_id' => $product->id,
        'primary_option_id' => $option1->id,
        'dependent_option_id' => $option2->id,
        'price_adjustment' => 25.00,
        'adjustment_type' => 'fixed',
        'description' => 'Test Price Rule',
        'active' => true,
    ]);

    $response = $this->getJson(route('admin.products.price-rules.index', $product));

    $response->assertStatus(200)
        ->assertJsonStructure([
            'priceRules'
        ])
        ->assertJson([
            'priceRules' => [
                [
                    'id' => $priceRule->id,
                    'primary_option_id' => $option1->id,
                    'dependent_option_id' => $option2->id,
                    'price_adjustment' => 25.00,
                ]
            ]
        ]);
});

test('it creates a new price rule for a product', function () {
    $product = ProductType::factory()->create();
    
    $part1 = Part::factory()->create(['product_type_id' => $product->id]);
    $part2 = Part::factory()->create(['product_type_id' => $product->id]);
    
    $option1 = PartOption::factory()->create(['part_id' => $part1->id]);
    $option2 = PartOption::factory()->create(['part_id' => $part2->id]);
    
    $priceRuleData = [
        'primary_option_id' => $option1->id,
        'dependent_option_id' => $option2->id,
        'price_adjustment' => 25.00,
        'adjustment_type' => 'fixed',
        'description' => 'Test Price Rule',
        'active' => true,
    ];

    $response = $this->postJson(route('admin.products.price-rules.store', $product), $priceRuleData);

    $response->assertStatus(201)
        ->assertJsonStructure([
            'message',
            'priceRule'
        ])
        ->assertJson([
            'message' => 'Price rule added successfully.',
            'priceRule' => [
                'primary_option_id' => $option1->id,
                'dependent_option_id' => $option2->id,
                'price_adjustment' => 25.00,
                'product_type_id' => $product->id,
            ]
        ]);

    $this->assertDatabaseHas('price_rules', [
        'primary_option_id' => $option1->id,
        'dependent_option_id' => $option2->id,
        'price_adjustment' => 25.00,
        'product_type_id' => $product->id,
    ]);
});

test('it updates a price rule', function () {
    $product = ProductType::factory()->create();
    
    $part1 = Part::factory()->create(['product_type_id' => $product->id]);
    $part2 = Part::factory()->create(['product_type_id' => $product->id]);
    $part3 = Part::factory()->create(['product_type_id' => $product->id]);
    
    $option1 = PartOption::factory()->create(['part_id' => $part1->id]);
    $option2 = PartOption::factory()->create(['part_id' => $part2->id]);
    $option3 = PartOption::factory()->create(['part_id' => $part3->id]);
    
    $priceRule = PriceRule::create([
        'product_type_id' => $product->id,
        'primary_option_id' => $option1->id,
        'dependent_option_id' => $option2->id,
        'price_adjustment' => 25.00,
        'adjustment_type' => 'fixed',
        'description' => 'Original Price Rule',
        'active' => true,
    ]);

    $updateData = [
        'primary_option_id' => $option1->id,
        'dependent_option_id' => $option3->id,
        'price_adjustment' => 50.00,
        'adjustment_type' => 'percentage',
        'description' => 'Updated Price Rule',
        'active' => false,
    ];

    $response = $this->putJson(route('admin.price-rules.update', $priceRule), $updateData);

    $response->assertStatus(200)
        ->assertJsonStructure([
            'message',
            'priceRule'
        ])
        ->assertJson([
            'message' => 'Price rule updated successfully.',
            'priceRule' => [
                'id' => $priceRule->id,
                'primary_option_id' => $option1->id,
                'dependent_option_id' => $option3->id,
                'price_adjustment' => 50.00,
            ]
        ]);

    $this->assertDatabaseHas('price_rules', [
        'id' => $priceRule->id,
        'primary_option_id' => $option1->id,
        'dependent_option_id' => $option3->id,
        'price_adjustment' => 50.00,
        'adjustment_type' => 'percentage',
        'description' => 'Updated Price Rule',
    ]);
});

test('it deletes a price rule', function () {
    $product = ProductType::factory()->create();
    
    $part1 = Part::factory()->create(['product_type_id' => $product->id]);
    $part2 = Part::factory()->create(['product_type_id' => $product->id]);
    
    $option1 = PartOption::factory()->create(['part_id' => $part1->id]);
    $option2 = PartOption::factory()->create(['part_id' => $part2->id]);
    
    $priceRule = PriceRule::create([
        'product_type_id' => $product->id,
        'primary_option_id' => $option1->id,
        'dependent_option_id' => $option2->id,
        'price_adjustment' => 25.00,
        'adjustment_type' => 'fixed',
        'description' => 'Test Price Rule',
        'active' => true,
    ]);

    $response = $this->deleteJson(route('admin.price-rules.destroy', $priceRule));

    $response->assertStatus(200)
        ->assertJsonStructure([
            'message'
        ])
        ->assertJson([
            'message' => 'Price rule deleted successfully.'
        ]);

    $this->assertDatabaseMissing('price_rules', [
        'id' => $priceRule->id,
    ]);
});

test('it requires authentication for all endpoints', function () {
    auth()->forgetGuards();
    $product = ProductType::factory()->create();
    $part = Part::factory()->create(['product_type_id' => $product->id]);
    $option = PartOption::factory()->create(['part_id' => $part->id]);
    $priceRule = PriceRule::factory()->create([
        'product_type_id' => $product->id,'primary_option_id' => $option->id,'dependent_option_id' => $option->id,'price_adjustment' => 10.00
    ]);

    $this->getJson(route('admin.products.price-rules.index', $product))->assertStatus(401);
    $this->postJson(route('admin.products.price-rules.store', $product), [])->assertStatus(401);
    $this->putJson(route('admin.price-rules.update', $priceRule), [])->assertStatus(401);
    $this->deleteJson(route('admin.price-rules.destroy', $priceRule))->assertStatus(401);
});