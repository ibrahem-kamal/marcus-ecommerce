# Main User Actions

This document describes the main actions users can take on the Marcus E-Commerce Platform.

## Customer Actions

### 1. Browse Products

Customers can browse the available products on the platform. The product listing page displays all active products with their names, descriptions, and images.

**Implementation Details:**
- The `ProductApiController@index` method retrieves all active products.
- The `Products/Index.vue` component displays the products in a grid layout.

### 2. Configure a Product

Customers can configure a product by selecting options for each part. The product configuration page displays all parts and their available options, with pricing information.

**Implementation Details:**
- The `ProductApiController@show` method retrieves a product with its parts, options, option combinations, and price rules.
- The `Products/Show.vue` component displays the product configuration interface.
- As the customer selects options, the system:
  - Updates the available options based on option combinations
  - Calculates the total price based on the selected options and price rules
  - Provides feedback on required or prohibited combinations

### 3. Add to Cart

Customers can add a configured product to their shopping cart. The system validates the configuration and calculates the final price before adding it to the cart.

**Implementation Details:**
- The `CartApiController@addToCart` method adds an item to the cart.
- The `CreateCartAction` handles the business logic of creating a cart item.
- The system:
  - Validates that all required parts have selected options
  - Validates that the selected options are valid combinations
  - Calculates the final price including all price adjustments
  - Creates a cart item with the selected options and price

### 4. View Cart

Customers can view their shopping cart to see the items they've added, with their configurations and prices.

**Implementation Details:**
- The `CartApiController@getCart` method retrieves the cart items for a given cart ID.
- The cart page displays the items in the cart, with their configurations and prices.

## Admin Actions

### 1. Manage Products

Administrators can manage products, including creating, updating, and deleting products.

**Implementation Details:**
- The `AdminProductApiController` provides CRUD operations for products.
- The admin product management pages allow administrators to:
  - View a list of all products
  - Create a new product
  - Edit an existing product
  - Delete a product

### 2. Manage Parts

Administrators can manage parts for a product, including creating, updating, and deleting parts.

**Implementation Details:**
- The `AdminPartApiController` provides CRUD operations for parts.
- The admin part management pages allow administrators to:
  - View a list of all parts for a product
  - Create a new part
  - Edit an existing part
  - Delete a part

### 3. Manage Part Options

Administrators can manage options for a part, including creating, updating, and deleting options.

**Implementation Details:**
- The `AdminPartOptionApiController` provides CRUD operations for part options.
- The admin part option management pages allow administrators to:
  - View a list of all options for a part
  - Create a new option
  - Edit an existing option
  - Delete an option

### 4. Manage Option Combinations

Administrators can manage option combinations, which define rules for valid combinations of options.

**Implementation Details:**
- The `AdminOptionCombinationApiController` provides CRUD operations for option combinations.
- The admin option combination management pages allow administrators to:
  - View a list of all option combinations for a product
  - Create a new option combination
  - Edit an existing option combination
  - Delete an option combination

### 5. Manage Price Rules

Administrators can manage price rules, which define pricing adjustments for combinations of options.

**Implementation Details:**
- The `AdminPriceRuleApiController` provides CRUD operations for price rules.
- The admin price rule management pages allow administrators to:
  - View a list of all price rules for a product
  - Create a new price rule
  - Edit an existing price rule
  - Delete a price rule