<?php

namespace Tests\Unit\Actions\Customer\Cart;

use App\Actions\Customer\Cart\CreateCartAction;
use App\Models\Cart;
use App\Models\ProductType;
use App\Models\User;
use App\Services\Cart\ValidateCartService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;

uses(RefreshDatabase::class);

afterEach(function () {
    Mockery::close();
});

test('it creates a cart item for guest user', function () {
    $product = ProductType::factory()->create();
    $cartId = 'test-cart-id';
    $data = [
        'product_id' => $product->id,
        'selected_options' => [
            'options' => [
                '1' => [
                    'partId' => 1,
                    'partName' => 'Part 1',
                    'optionId' => 1,
                    'optionName' => 'Option 1',
                    'price' => 100.00
                ]
            ]
        ],
        'price_adjustments' => [
            [
                'description' => 'Adjustment 1',
                'amount' => 10.00
            ]
        ],
        'total_price' => 110.00,
    ];

    $validateCartService = Mockery::mock(ValidateCartService::class);
    $validateCartService->shouldReceive('validate')
        ->once()
        ->with($data)
        ->andReturn(array_merge($data, ['_product' => $product]));

    $action = new CreateCartAction($validateCartService);

    $cart = $action->handle($cartId, $data);

    expect($cart)->toBeInstanceOf(Cart::class);
    expect($cart->cart_id)->toBe($cartId);
    expect($cart->product_id)->toBe($product->id);
    expect($cart->user_id)->toBeNull();
    expect($cart->selected_options)->toEqual($data['selected_options']);
    expect($cart->price_adjustments)->toEqual($data['price_adjustments']);
    expect($cart->total_price)->toEqual($data['total_price']);
});

test('it creates a cart item for authenticated user', function () {
    $product = ProductType::factory()->create();
    $user = User::factory()->create();
    $cartId = 'test-cart-id';
    $data = [
        'product_id' => $product->id,
        'selected_options' => [
            'options' => [
                '1' => [
                    'partId' => 1,
                    'partName' => 'Part 1',
                    'optionId' => 1,
                    'optionName' => 'Option 1',
                    'price' => 100.00
                ]
            ]
        ],
        'price_adjustments' => [
            [
                'description' => 'Adjustment 1',
                'amount' => 10.00
            ]
        ],
        'total_price' => 110.00,
    ];

    $validateCartService = Mockery::mock(ValidateCartService::class);
    $validateCartService->shouldReceive('validate')
        ->once()
        ->with($data)
        ->andReturn(array_merge($data, ['_product' => $product]));

    $action = new CreateCartAction($validateCartService);

    $cart = $action->handle($cartId, $data, $user);

    expect($cart)->toBeInstanceOf(Cart::class);
    expect($cart->cart_id)->toBe($cartId);
    expect($cart->product_id)->toBe($product->id);
    expect($cart->user_id)->toBe($user->id);
    expect($cart->selected_options)->toEqual($data['selected_options']);
    expect($cart->price_adjustments)->toEqual($data['price_adjustments']);
    expect($cart->total_price)->toEqual($data['total_price']);
});

test('it allows multiple cart items with same product id', function () {
    $product = ProductType::factory()->create();
    $cartId = 'test-cart-id';

    $firstItemData = [
        'product_id' => $product->id,
        'selected_options' => [
            'options' => [
                '1' => [
                    'partId' => 1,
                    'partName' => 'Part 1',
                    'optionId' => 1,
                    'optionName' => 'Option 1',
                    'price' => 100.00
                ]
            ]
        ],
        'price_adjustments' => [],
        'total_price' => 100.00,
    ];

    $validateCartService1 = Mockery::mock(ValidateCartService::class);
    $validateCartService1->shouldReceive('validate')
        ->once()
        ->with($firstItemData)
        ->andReturn(array_merge($firstItemData, ['_product' => $product]));

    $action1 = new CreateCartAction($validateCartService1);
    $action1->handle($cartId, $firstItemData);

    $secondItemData = [
        'product_id' => $product->id,
        'selected_options' => [
            'options' => [
                '1' => [
                    'partId' => 1,
                    'partName' => 'Part 1',
                    'optionId' => 2,
                    'optionName' => 'Option 2',
                    'price' => 150.00
                ]
            ]
        ],
        'price_adjustments' => [
            [
                'description' => 'Adjustment 1',
                'amount' => 20.00
            ]
        ],
        'total_price' => 170.00,
    ];

    $validateCartService2 = Mockery::mock(ValidateCartService::class);
    $validateCartService2->shouldReceive('validate')
        ->once()
        ->with($secondItemData)
        ->andReturn(array_merge($secondItemData, ['_product' => $product]));

    $action2 = new CreateCartAction($validateCartService2);

    $cart = $action2->handle($cartId, $secondItemData);

    expect($cart)->toBeInstanceOf(Cart::class);
    expect($cart->cart_id)->toBe($cartId);
    expect($cart->product_id)->toBe($product->id);
    expect($cart->selected_options)->toEqual($secondItemData['selected_options']);
    expect($cart->price_adjustments)->toEqual($secondItemData['price_adjustments']);
    expect($cart->total_price)->toEqual($secondItemData['total_price']);

    expect(Cart::count())->toBe(2);

    $cartItems = Cart::all();
    expect($cartItems[0]->product_id)->toBe($product->id);
    expect($cartItems[1]->product_id)->toBe($product->id);
});

test('it allows multiple cart items with different product ids', function () {
    $product1 = ProductType::factory()->create();
    $product2 = ProductType::factory()->create();
    $cartId = 'test-cart-id';

    $firstItemData = [
        'product_id' => $product1->id,
        'selected_options' => [
            'options' => [
                '1' => [
                    'partId' => 1,
                    'partName' => 'Part 1',
                    'optionId' => 1,
                    'optionName' => 'Option 1',
                    'price' => 100.00
                ]
            ]
        ],
        'price_adjustments' => [],
        'total_price' => 100.00,
    ];

    $validateCartService1 = Mockery::mock(ValidateCartService::class);
    $validateCartService1->shouldReceive('validate')
        ->once()
        ->with($firstItemData)
        ->andReturn(array_merge($firstItemData, ['_product' => $product1]));

    $action1 = new CreateCartAction($validateCartService1);
    $action1->handle($cartId, $firstItemData);

    $secondItemData = [
        'product_id' => $product2->id,
        'selected_options' => [
            'options' => [
                '1' => [
                    'partId' => 1,
                    'partName' => 'Part 1',
                    'optionId' => 1,
                    'optionName' => 'Option 1',
                    'price' => 150.00
                ]
            ]
        ],
        'price_adjustments' => [],
        'total_price' => 150.00,
    ];

    $validateCartService2 = Mockery::mock(ValidateCartService::class);
    $validateCartService2->shouldReceive('validate')
        ->once()
        ->with($secondItemData)
        ->andReturn(array_merge($secondItemData, ['_product' => $product2]));

    $action2 = new CreateCartAction($validateCartService2);

    $cart = $action2->handle($cartId, $secondItemData);

    expect($cart)->toBeInstanceOf(Cart::class);
    expect($cart->cart_id)->toBe($cartId);
    expect($cart->product_id)->toBe($product2->id);
    expect($cart->selected_options)->toEqual($secondItemData['selected_options']);
    expect($cart->price_adjustments)->toEqual($secondItemData['price_adjustments']);
    expect($cart->total_price)->toEqual($secondItemData['total_price']);

    expect(Cart::count())->toBe(2);

    $cartItems = Cart::all();
    expect($cartItems[0]->product_id)->toBe($product1->id);
    expect($cartItems[1]->product_id)->toBe($product2->id);
});
