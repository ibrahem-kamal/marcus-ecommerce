<?php

namespace App\Http\Controllers\Api\Customer;

use App\Actions\Customer\Cart\CreateCartAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateCartRequest;
use App\Models\Cart;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CartApiController extends Controller
{
    /**
     * Add an item to the cart.
     *
     * @param CreateCartRequest $request
     * @param CreateCartAction $action
     * @return JsonResponse
     */
    public function addToCart(CreateCartRequest $request, CreateCartAction $action): JsonResponse
    {
        $user = $request->user();
        $cartId = $request->input('cart_id');
        $data = $request->validated();

        $cart = $action->handle($cartId, $data, $user);

        return response()->json([
            'message' => 'Item added to cart successfully',
            'cart' => $cart
        ]);
    }

    /**
     * Get cart data by cart_id.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getCart(Request $request): JsonResponse
    {
        $cartId = $request->input('cart_id');

        if (!$cartId) {
            return response()->json([
                'exists' => false,
                'message' => 'Cart ID is required'
            ], 400);
        }

        $user = $request->user();
        $query = Cart::query()->where('cart_id', $cartId);

        if ($user) {
            $query->where(function($q) use ($user) {
                $q->where('user_id', $user->id);
            });
        }

        $cartItems = $query->with('product')->get();

        if ($cartItems->isEmpty()) {
            return response()->json([
                'exists' => false,
                'message' => 'Cart not found'
            ]);
        }

        return response()->json([
            'exists' => true,
            'cart_items' => $cartItems
        ]);
    }
}
