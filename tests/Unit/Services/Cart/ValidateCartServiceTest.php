<?php

namespace Tests\Unit\Services\Cart;

use App\Models\Part;
use App\Models\PartOption;
use App\Models\ProductType;
use App\Models\OptionCombination;
use App\Models\PriceRule;
use App\Services\Cart\ValidateCartService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->service = new ValidateCartService();
    $this->product = ProductType::factory()->create(['active' => true]);

    $this->part1 = Part::factory()->create([
        'product_type_id' => $this->product->id,
        'name' => 'Part 1',
        'required' => true,
        'active' => true,
    ]);

    $this->part2 = Part::factory()->create([
        'product_type_id' => $this->product->id,
        'name' => 'Part 2',
        'required' => false,
        'active' => true,
    ]);

    $this->option1 = PartOption::factory()->create([
        'part_id' => $this->part1->id,
        'name' => 'Option 1',
        'base_price' => 100.00,
        'active' => true,
        'in_stock' => true,
    ]);

    $this->option2 = PartOption::factory()->create([
        'part_id' => $this->part1->id,
        'name' => 'Option 2',
        'base_price' => 150.00,
        'active' => true,
        'in_stock' => true,
    ]);

    $this->option3 = PartOption::factory()->create([
        'part_id' => $this->part2->id,
        'name' => 'Option 3',
        'base_price' => 50.00,
        'active' => true,
        'in_stock' => true,
    ]);

    $this->option4 = PartOption::factory()->create([
        'part_id' => $this->part2->id,
        'name' => 'Option 4',
        'base_price' => 75.00,
        'active' => true,
        'in_stock' => true,
    ]);
});

test('it validates valid cart data', function () {
    $data = [
        'cart_id' => 'test-cart-id',
        'product_id' => $this->product->id,
        'selected_options' => [
            'options' => [
                $this->part1->id => [
                    'partId' => $this->part1->id,
                    'partName' => 'Part 1',
                    'optionId' => $this->option1->id,
                    'optionName' => 'Option 1',
                    'price' => 100.00
                ],
                $this->part2->id => [
                    'partId' => $this->part2->id,
                    'partName' => 'Part 2',
                    'optionId' => $this->option3->id,
                    'optionName' => 'Option 3',
                    'price' => 50.00
                ]
            ]
        ],
        'price_adjustments' => [],
        'total_price' => 150.00,
    ];

    $validatedData = $this->service->validate($data);

    expect($validatedData)->toHaveKey('_product');
    expect($validatedData['_product']->id)->toBe($this->product->id);
});

test('it validates required parts have options', function () {
    $data = [
        'cart_id' => 'test-cart-id',
        'product_id' => $this->product->id,
        'selected_options' => [
            'options' => [
                $this->part2->id => [
                    'partId' => $this->part2->id,
                    'partName' => 'Part 2',
                    'optionId' => $this->option3->id,
                    'optionName' => 'Option 3',
                    'price' => 50.00
                ]
            ]
        ],
        'price_adjustments' => [],
        'total_price' => 50.00,
    ];

    expect(fn () => $this->service->validate($data))
        ->toThrow(ValidationException::class, "The required part 'Part 1' does not have a selected option.");
});

test('it validates option combinations required', function () {
    OptionCombination::factory()->create([
        'product_type_id' => $this->product->id,
        'if_option_id' => $this->option1->id,
        'then_option_id' => $this->option3->id,
        'then_part_id' => $this->part2->id,
        'rule_type' => 'required',
        'active' => true,
    ]);

    $data = [
        'cart_id' => 'test-cart-id',
        'product_id' => $this->product->id,
        'selected_options' => [
            'options' => [
                $this->part1->id => [
                    'partId' => $this->part1->id,
                    'partName' => 'Part 1',
                    'optionId' => $this->option1->id,
                    'optionName' => 'Option 1',
                    'price' => 100.00
                ],
                $this->part2->id => [
                    'partId' => $this->part2->id,
                    'partName' => 'Part 2',
                    'optionId' => $this->option4->id,
                    'optionName' => 'Option 4',
                    'price' => 75.00
                ]
            ]
        ],
        'price_adjustments' => [],
        'total_price' => 175.00,
    ];

    expect(fn () => $this->service->validate($data))
        ->toThrow(ValidationException::class, "When 'Option 1' is selected, 'Option 3' must also be selected.");
});

test('it validates option combinations prohibited', function () {
    OptionCombination::factory()->create([
        'product_type_id' => $this->product->id,
        'if_option_id' => $this->option1->id,
        'then_option_id' => $this->option4->id,
        'then_part_id' => $this->part2->id,
        'rule_type' => 'prohibited',
        'active' => true,
    ]);

    $data = [
        'cart_id' => 'test-cart-id',
        'product_id' => $this->product->id,
        'selected_options' => [
            'options' => [
                $this->part1->id => [
                    'partId' => $this->part1->id,
                    'partName' => 'Part 1',
                    'optionId' => $this->option1->id,
                    'optionName' => 'Option 1',
                    'price' => 100.00
                ],
                $this->part2->id => [
                    'partId' => $this->part2->id,
                    'partName' => 'Part 2',
                    'optionId' => $this->option4->id,
                    'optionName' => 'Option 4',
                    'price' => 75.00
                ]
            ]
        ],
        'price_adjustments' => [],
        'total_price' => 175.00,
    ];

    expect(fn () => $this->service->validate($data))
        ->toThrow(ValidationException::class, "When 'Option 1' is selected, 'Option 4' cannot be selected.");
});

test('it validates price calculations', function () {
    PriceRule::factory()->create([
        'product_type_id' => $this->product->id,
        'primary_option_id' => $this->option1->id,
        'dependent_option_id' => $this->option3->id,
        'price_adjustment' => -10.00,
        'adjustment_type' => 'fixed',
        'active' => true,
    ]);

    $data = [
        'cart_id' => 'test-cart-id',
        'product_id' => $this->product->id,
        'selected_options' => [
            'options' => [
                $this->part1->id => [
                    'partId' => $this->part1->id,
                    'partName' => 'Part 1',
                    'optionId' => $this->option1->id,
                    'optionName' => 'Option 1',
                    'price' => 100.00
                ],
                $this->part2->id => [
                    'partId' => $this->part2->id,
                    'partName' => 'Part 2',
                    'optionId' => $this->option3->id,
                    'optionName' => 'Option 3',
                    'price' => 50.00
                ]
            ]
        ],
        'price_adjustments' => [],
        'total_price' => 150.00,
    ];

    expect(fn () => $this->service->validate($data))
        ->toThrow(ValidationException::class, "The price adjustments are incorrect.");
});

test('it validates total price', function () {
    $data = [
        'cart_id' => 'test-cart-id',
        'product_id' => $this->product->id,
        'selected_options' => [
            'options' => [
                $this->part1->id => [
                    'partId' => $this->part1->id,
                    'partName' => 'Part 1',
                    'optionId' => $this->option1->id,
                    'optionName' => 'Option 1',
                    'price' => 100.00
                ],
                $this->part2->id => [
                    'partId' => $this->part2->id,
                    'partName' => 'Part 2',
                    'optionId' => $this->option3->id,
                    'optionName' => 'Option 3',
                    'price' => 50.00
                ]
            ]
        ],
        'price_adjustments' => [],
        'total_price' => 160.00,
    ];

    expect(fn () => $this->service->validate($data))
        ->toThrow(ValidationException::class, "The total price is incorrect.");
});