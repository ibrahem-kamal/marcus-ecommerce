# Setting Prices

This document describes how to set and manage prices for products in the Marcus E-Commerce Platform.

## Overview

In the Marcus E-Commerce Platform, pricing is determined by a combination of base prices for individual options and price rules for combinations of options. This flexible pricing model allows for complex pricing scenarios, such as discounts for certain combinations or premium pricing for others.

## Pricing Components

The pricing system consists of two main components:

1. **Base Prices**: Each option has a base price, which is the starting point for calculating the total price.
2. **Price Rules**: Price rules define adjustments to the price based on combinations of options.

## Setting Base Prices

Base prices are set at the option level. When creating or editing an option, you can specify its base price.

### Step-by-Step Process

1. Log in to the admin interface.
2. Navigate to the option management page for a part.
3. Click the "Create Option" button or the "Edit" button for an existing option.
4. Enter the base price in the "Base Price" field.
5. Click the "Save" button.

### Example

For a bicycle product, you might set the following base prices:

- Aluminum Frame: $200
- Carbon Frame: $500
- Steel Frame: $300
- Basic Wheels: $100
- Premium Wheels: $300
- Basic Handlebars: $50
- Premium Handlebars: $150

## Setting Price Rules

Price rules define adjustments to the price based on combinations of options. For example, you might offer a discount when a customer selects both a carbon frame and premium wheels, or you might charge a premium for a certain combination of options.

### Step-by-Step Process

1. Log in to the admin interface.
2. Click on "Price Rules" in the navigation menu.
3. Click the "Create Price Rule" button.
4. Enter the price rule details:
   - **Product**: Select the product for which you're defining the price rule.
   - **Primary Option**: Select the primary option that triggers the price adjustment.
   - **Dependent Option**: Select the dependent option that, when selected along with the primary option, triggers the price adjustment.
   - **Price Adjustment**: Enter the amount of the price adjustment. Use a negative value for a discount.
   - **Adjustment Type**: Select "Fixed" if the adjustment is a fixed amount, or "Percentage" if the adjustment is a percentage of the base price.
   - **Description**: Enter a description of the price rule to help customers understand why the price is adjusted.
   - **Active**: Check this box to make the rule active.
5. Click the "Save" button.

### Example

For a bicycle product, you might set the following price rules:

1. **Carbon Frame + Premium Wheels Discount**:
   - Primary Option: Carbon Frame
   - Dependent Option: Premium Wheels
   - Price Adjustment: -100.00
   - Adjustment Type: Fixed
   - Description: "Discount for carbon frame with premium wheels"

2. **Premium Package Discount**:
   - Primary Option: Carbon Frame
   - Dependent Option: Premium Handlebars
   - Price Adjustment: -10
   - Adjustment Type: Percentage
   - Description: "10% discount on premium package"

## Price Calculation

The system calculates the total price of a configured product as follows:

1. **Base Price**: The sum of the base prices of all selected options.
2. **Price Adjustments**: Any price adjustments defined by price rules for combinations of selected options.
3. **Total Price**: The base price plus all price adjustments.

### Example

If a customer selects:
- Carbon Frame: $500
- Premium Wheels: $300
- Premium Handlebars: $150

The price calculation would be:
1. Base Price: $500 + $300 + $150 = $950
2. Price Adjustments:
   - Carbon Frame + Premium Wheels: -$100
   - Carbon Frame + Premium Handlebars: -10% of $950 = -$95
3. Total Price: $950 - $100 - $95 = $755

## Special Pricing Scenarios

### Bulk Discounts

To implement bulk discounts (e.g., discounts for ordering multiple items), you can create price rules with percentage adjustments. For example:

1. **Bulk Discount**:
   - Primary Option: Any option
   - Dependent Option: The same option
   - Price Adjustment: -5
   - Adjustment Type: Percentage
   - Description: "5% discount for bulk orders"

### Seasonal Pricing

To implement seasonal pricing, you can update the base prices of options or create time-limited price rules. For example:

1. **Summer Sale**:
   - Primary Option: Any option
   - Dependent Option: Any option
   - Price Adjustment: -15
   - Adjustment Type: Percentage
   - Description: "Summer sale - 15% off"
   - Active: Check this box during the sale period, uncheck it when the sale ends.

### Premium Combinations

To charge a premium for certain combinations of options, you can create price rules with positive price adjustments. For example:

1. **Premium Combination**:
   - Primary Option: Carbon Frame
   - Dependent Option: Custom Paint
   - Price Adjustment: 200.00
   - Adjustment Type: Fixed
   - Description: "Premium for custom paint on carbon frame"

## Database Changes

When you set prices, the following changes are made to the database:

1. When you set a base price for an option, the `base_price` field in the `part_options` table is updated.
2. When you create a price rule, a new record is created in the `price_rules` table with the rule details and foreign keys to the product and options.

## Implementation Details

The pricing system is implemented using the following components:

- **AdminPartOptionApiController**: Handles updating base prices for options.
- **AdminPriceRuleApiController**: Handles CRUD operations for price rules.
- **Products/Show.vue**: Calculates and displays the price based on the selected options and price rules.

## Best Practices

When setting prices, consider the following best practices:

1. **Be transparent about pricing**: Make sure customers understand how the price is calculated and why certain combinations affect the price.
2. **Use descriptive names for price rules**: The description of a price rule should clearly explain why the price is adjusted.
3. **Test price calculations**: After setting up price rules, test various combinations of options to ensure that the prices are calculated correctly.
4. **Consider the customer experience**: Avoid complex pricing schemes that might confuse customers.
5. **Regularly review and update prices**: Market conditions change, so regularly review and update your prices to stay competitive.
6. **Document your pricing strategy**: Keep a record of your pricing strategy, including the rationale for base prices and price rules, to ensure consistency over time.