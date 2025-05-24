<?php

use App\Models\Admin;
use App\Models\OptionCombination;
use App\Models\Part;
use App\Models\PartOption;
use App\Models\ProductType;
use Laravel\Sanctum\Sanctum;

beforeEach(function () {
    $admin = Admin::factory()->create();
    Sanctum::actingAs($admin, ['*']);
});

test('it lists option combinations for a product', function () {
    $product = ProductType::factory()->create();
    
    $part1 = Part::factory()->create(['product_type_id' => $product->id]);
    $part2 = Part::factory()->create(['product_type_id' => $product->id]);
    
    $option1 = PartOption::factory()->create(['part_id' => $part1->id]);
    $option2 = PartOption::factory()->create(['part_id' => $part2->id]);
    
    $combination = OptionCombination::create([
        'product_type_id' => $product->id,
        'if_option_id' => $option1->id,
        'then_part_id' => $part2->id,
        'then_option_id' => $option2->id,
        'rule_type' => 'required',
        'description' => 'Test Combination',
        'active' => true,
    ]);

    $response = $this->getJson(route('admin.products.combinations.index', $product));

    $response->assertStatus(200)
        ->assertJsonStructure([
            'combinations'
        ])
        ->assertJson([
            'combinations' => [
                [
                    'id' => $combination->id,
                    'if_option_id' => $option1->id,
                    'then_part_id' => $part2->id,
                    'then_option_id' => $option2->id,
                ]
            ]
        ]);
});

test('it creates a new option combination for a product', function () {
    $product = ProductType::factory()->create();
    
    $part1 = Part::factory()->create(['product_type_id' => $product->id]);
    $part2 = Part::factory()->create(['product_type_id' => $product->id]);
    
    $option1 = PartOption::factory()->create(['part_id' => $part1->id]);
    $option2 = PartOption::factory()->create(['part_id' => $part2->id]);
    
    $combinationData = [
        'if_option_id' => $option1->id,
        'then_part_id' => $part2->id,
        'then_option_id' => $option2->id,
        'rule_type' => 'required',
        'description' => 'Test Combination',
        'active' => true,
    ];

    $response = $this->postJson(route('admin.products.combinations.store', $product), $combinationData);

    $response->assertStatus(201)
        ->assertJsonStructure([
            'message',
            'combination'
        ])
        ->assertJson([
            'message' => 'Option combination added successfully.',
            'combination' => [
                'if_option_id' => $option1->id,
                'then_part_id' => $part2->id,
                'then_option_id' => $option2->id,
                'product_type_id' => $product->id,
            ]
        ]);

    $this->assertDatabaseHas('option_combinations', [
        'if_option_id' => $option1->id,
        'then_part_id' => $part2->id,
        'then_option_id' => $option2->id,
        'product_type_id' => $product->id,
    ]);
});

test('it updates an option combination', function () {
    $product = ProductType::factory()->create();
    
    $part1 = Part::factory()->create(['product_type_id' => $product->id]);
    $part2 = Part::factory()->create(['product_type_id' => $product->id]);
    $part3 = Part::factory()->create(['product_type_id' => $product->id]);
    
    $option1 = PartOption::factory()->create(['part_id' => $part1->id]);
    $option2 = PartOption::factory()->create(['part_id' => $part2->id]);
    $option3 = PartOption::factory()->create(['part_id' => $part3->id]);
    
    $combination = OptionCombination::create([
        'product_type_id' => $product->id,
        'if_option_id' => $option1->id,
        'then_part_id' => $part2->id,
        'then_option_id' => $option2->id,
        'rule_type' => 'required',
        'description' => 'Original Combination',
        'active' => true,
    ]);

    $updateData = [
        'if_option_id' => $option1->id,
        'then_part_id' => $part3->id,
        'then_option_id' => $option3->id,
        'rule_type' => 'prohibited',
        'description' => 'Updated Combination',
        'active' => false,
    ];

    $response = $this->putJson(route('admin.combinations.update', $combination), $updateData);

    $response->assertStatus(200)
        ->assertJsonStructure([
            'message',
            'combination'
        ])
        ->assertJson([
            'message' => 'Option combination updated successfully.',
            'combination' => [
                'id' => $combination->id,
                'if_option_id' => $option1->id,
                'then_part_id' => $part3->id,
                'then_option_id' => $option3->id,
            ]
        ]);

    $this->assertDatabaseHas('option_combinations', [
        'id' => $combination->id,
        'if_option_id' => $option1->id,
        'then_part_id' => $part3->id,
        'then_option_id' => $option3->id,
        'rule_type' => 'prohibited',
        'description' => 'Updated Combination',
    ]);
});

test('it deletes an option combination', function () {
    $product = ProductType::factory()->create();
    
    $part1 = Part::factory()->create(['product_type_id' => $product->id]);
    $part2 = Part::factory()->create(['product_type_id' => $product->id]);
    
    $option1 = PartOption::factory()->create(['part_id' => $part1->id]);
    $option2 = PartOption::factory()->create(['part_id' => $part2->id]);
    
    $combination = OptionCombination::create([
        'product_type_id' => $product->id,
        'if_option_id' => $option1->id,
        'then_part_id' => $part2->id,
        'then_option_id' => $option2->id,
        'rule_type' => 'required',
        'description' => 'Test Combination',
        'active' => true,
    ]);

    $response = $this->deleteJson(route('admin.combinations.destroy', $combination));

    $response->assertStatus(200)
        ->assertJsonStructure([
            'message'
        ])
        ->assertJson([
            'message' => 'Option combination deleted successfully.'
        ]);

    $this->assertDatabaseMissing('option_combinations', [
        'id' => $combination->id,
    ]);
});

test('it requires authentication for all endpoints', function () {
    auth()->forgetGuards();
    $product = ProductType::factory()->create();
    $part = Part::factory()->create(['product_type_id' => $product->id]);
    $option = PartOption::factory()->create(['part_id' => $part->id]);
    $combination = OptionCombination::factory()->create([
        'product_type_id' => $product->id,
        'if_option_id' => $option->id,
        'then_part_id' => $part->id,
        'then_option_id' => $option->id,
    ]);

    $this->getJson(route('admin.products.combinations.index', $product))->assertStatus(401);
    $this->postJson(route('admin.products.combinations.store', $product), [])->assertStatus(401);
    $this->putJson(route('admin.combinations.update', $combination), [])->assertStatus(401);
    $this->deleteJson(route('admin.combinations.destroy', $combination))->assertStatus(401);
});