<?php

namespace App\Actions\Customer\Cart;

use App\Models\Cart;
use App\Models\ProductType;
use App\Models\User;
use App\Services\Cart\ValidateCartService;

class CreateCartAction
{
    /**
     * @var ValidateCartService
     */
    protected ValidateCartService $validateCartService;

    /**
     * Constructor.
     *
     * @param ValidateCartService $validateCartService
     */
    public function __construct(ValidateCartService $validateCartService)
    {
        $this->validateCartService = $validateCartService;
    }

    /**
     * Add an item to the cart.
     *
     * @param string $cartId The unique cart identifier
     * @param array $data The cart item data
     * @param User|null $user The authenticated user (if any)
     * @return Cart The created cart item
     */
    public function handle(string $cartId, array $data, ?User $user = null): Cart
    {
        // Validate the cart data
        $validatedData = $this->validateCartService->validate($data);

        // Get the validated product
        $product = $validatedData['_product'];

        // Always create a new cart item to allow multiple products in the cart
        $cart = new Cart();
        $cart->cart_id = $cartId;
        $cart->product_id = $product->id;
        $cart->user_id = $user?->id;

        $cart->selected_options = $data['selected_options'];
        $cart->price_adjustments = $data['price_adjustments'] ?? [];
        $cart->total_price = $data['total_price'];
        $cart->save();

        return $cart;
    }
}
