# Add to Cart Action

This document describes the "Add to Cart" functionality in the Marcus E-Commerce Platform.

## Overview

The "Add to Cart" action allows customers to add a configured product to their shopping cart. The system validates the configuration, calculates the final price, and creates a cart item with the selected options.

## User Flow

1. The customer configures a product by selecting options for each part on the product page.
2. The customer clicks the "Add to Cart" button.
3. The system validates the configuration to ensure all required parts have selected options and all selected options are valid combinations.
4. The system calculates the final price based on the selected options and any applicable price rules.
5. The system creates a cart item with the selected options and price.
6. The system displays a success message and provides a link to view the cart.

## Implementation Details

### Frontend Implementation

The "Add to Cart" functionality is implemented in the `Products/Show.vue` component. When the customer clicks the "Add to Cart" button, the component:

1. Validates that all required parts have selected options.
2. Makes an API request to `CartApiController@addToCart` with the following data:
   - `product_id`: The ID of the product being configured
   - `selected_options`: An object mapping part IDs to selected option IDs
   - `cart_id`: An optional cart ID if the customer already has a cart

```javascript
async function addToCart() {
  // Validate that all required parts have selected options
  const missingRequiredParts = product.parts
    .filter(part => part.required && !selectedOptions[part.id])
    .map(part => part.name);
    
  if (missingRequiredParts.length > 0) {
    errorMessage.value = `Please select options for the following required parts: ${missingRequiredParts.join(', ')}`;
    return;
  }
  
  try {
    const response = await axios.post('/api/cart/add', {
      product_id: product.id,
      selected_options: selectedOptions,
      cart_id: localStorage.getItem('cart_id') || null
    });
    
    // Store the cart ID for future use
    if (response.data.cart && response.data.cart.cart_id) {
      localStorage.setItem('cart_id', response.data.cart.cart_id);
    }
    
    // Display success message
    successMessage.value = 'Item added to cart successfully!';
    
    // Reset error message
    errorMessage.value = '';
  } catch (error) {
    errorMessage.value = error.response?.data?.message || 'Failed to add item to cart. Please try again.';
  }
}
```

### Backend Implementation

The backend implementation of the "Add to Cart" functionality is handled by the `CartApiController@addToCart` method and the `CreateCartAction` class.

#### CartApiController

```php
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
```

#### CreateCartAction

The `CreateCartAction` class handles the business logic of creating a cart item:

1. Validates the selected options against the product configuration.
2. Calculates the price based on the selected options and price rules.
3. Creates a cart item with the selected options and price.

```php
public function handle(?string $cartId, array $data, ?User $user = null): Cart
{
    // Generate a new cart ID if none is provided
    if (!$cartId) {
        $cartId = Str::uuid();
    }
    
    // Get the product
    $product = ProductType::findOrFail($data['product_id']);
    
    // Validate selected options
    $this->validateSelectedOptions($product, $data['selected_options']);
    
    // Calculate price
    $priceData = $this->calculatePrice($product, $data['selected_options']);
    
    // Create cart item
    $cart = new Cart();
    $cart->cart_id = $cartId;
    $cart->user_id = $user?->id;
    $cart->product_id = $product->id;
    $cart->selected_options = $data['selected_options'];
    $cart->price_adjustments = $priceData['adjustments'];
    $cart->total_price = $priceData['total_price'];
    $cart->save();
    
    return $cart;
}
```

## Data Persistence

When a product is added to the cart, the following data is persisted in the `carts` table:

| Field            | Description                                      |
|------------------|--------------------------------------------------|
| cart_id          | A unique identifier for the cart                 |
| user_id          | The ID of the user (if authenticated)            |
| product_id       | The ID of the product                            |
| selected_options | A JSON object mapping part IDs to option IDs     |
| price_adjustments| A JSON object containing price adjustment details|
| total_price      | The total price of the item                      |

## Cart Identification

The system uses a cart ID to identify carts for both guest and authenticated users:

- For guest users, a cart ID is generated and stored in localStorage.
- For authenticated users, the cart is associated with their user ID, but a cart ID is still used for consistency.

This approach allows for a seamless transition when a guest user logs in or creates an account.

## Price Calculation

The price calculation for cart items follows the same algorithm as described in the [Product Page](product-page.md) documentation:

1. Calculate the base price as the sum of the base prices of all selected options.
2. Apply any price adjustments defined by price rules for combinations of selected options.
3. Calculate the total price as the base price plus all price adjustments.

## Validation

Before adding an item to the cart, the system performs the following validations:

1. All required parts must have selected options.
2. All selected options must be valid combinations according to the option combination rules.
3. All selected options must be in stock.
4. All selected options must be active.

If any validation fails, the system returns an error message and the item is not added to the cart.