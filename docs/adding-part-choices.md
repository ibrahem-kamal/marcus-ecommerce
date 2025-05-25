# Adding Part Choices

This document describes how to add new part choices (options) to an existing product in the Marcus E-Commerce Platform.

## Overview

In the Marcus E-Commerce Platform, products are composed of parts (e.g., frame, wheels, handlebars), and each part has one or more options (e.g., aluminum frame, carbon frame, steel frame). This document explains how to add new options to existing parts, using the example of adding a new rim color to a bicycle.

## Prerequisites

Before adding a new part option, you should have:

1. An existing product with parts defined
2. Administrator access to the platform

## Step-by-Step Process

### 1. Navigate to the Part Management Page

1. Log in to the admin interface.
2. Click on "Products" in the navigation menu.
3. Find the product you want to modify and click on the "Parts" button.
4. The system displays the part management page with a list of parts for the product.

### 2. Find the Part to Modify

1. Locate the part you want to add an option to (e.g., "Rims").
2. Click on the "Options" button for that part.
3. The system displays the option management page with a list of existing options for the part.

### 3. Create a New Option

1. Click the "Create Option" button.
2. The system displays the option creation form.
3. Enter the option details:
   - **Name**: Enter a descriptive name for the option (e.g., "Red Rims").
   - **Description**: Enter a detailed description of the option.
   - **Base Price**: Enter the base price of the option.
   - **Display Order**: Enter a number to control the order in which the option appears in the UI.
   - **In Stock**: Check this box if the option is in stock.
   - **Active**: Check this box to make the option visible to customers.
4. Click the "Save" button.
5. The system validates the input and creates the option.
6. The system redirects you to the option management page, where the new option is now listed.

### 4. Update Option Combinations (if necessary)

If the new option has specific compatibility requirements with other options, you need to define option combinations.

1. Click on "Option Combinations" in the navigation menu.
2. The system displays the option combination management page.
3. Click the "Create Option Combination" button.
4. The system displays the option combination creation form.
5. Enter the option combination details:
   - **Product**: Select the product for which you're defining the option combination.
   - **If Option**: Select the option that triggers the rule (e.g., "Red Rims").
   - **Then Option/Part**: Select the option or part that is affected by the rule (e.g., "Red Handlebars").
   - **Rule Type**: Select "Required" if the then option/part is required when the if option is selected, or "Prohibited" if the then option/part is prohibited when the if option is selected.
   - **Description**: Enter a description of the rule to help customers understand why certain combinations are required or prohibited (e.g., "Red rims require red handlebars for a consistent look").
   - **Active**: Check this box to make the rule active.
6. Click the "Save" button.
7. The system validates the input and creates the option combination.
8. Repeat steps 3-7 for each option combination you want to define.

### 5. Update Price Rules (if necessary)

If the new option affects the pricing of other options, you need to define price rules.

1. Click on "Price Rules" in the navigation menu.
2. The system displays the price rule management page.
3. Click the "Create Price Rule" button.
4. The system displays the price rule creation form.
5. Enter the price rule details:
   - **Product**: Select the product for which you're defining the price rule.
   - **Primary Option**: Select the primary option that triggers the price adjustment (e.g., "Red Rims").
   - **Dependent Option**: Select the dependent option that, when selected along with the primary option, triggers the price adjustment (e.g., "Red Handlebars").
   - **Price Adjustment**: Enter the amount of the price adjustment (e.g., -50.00 for a discount).
   - **Adjustment Type**: Select "Fixed" if the adjustment is a fixed amount, or "Percentage" if the adjustment is a percentage of the base price.
   - **Description**: Enter a description of the price rule to help customers understand why the price is adjusted (e.g., "Discount for matching rim and handlebar colors").
   - **Active**: Check this box to make the rule active.
6. Click the "Save" button.
7. The system validates the input and creates the price rule.
8. Repeat steps 3-7 for each price rule you want to define.

## Database Changes

When you add a new part option, the following changes are made to the database:

1. A new record is created in the `part_options` table with the option details and a foreign key to the part.
2. If you define option combinations, new records are created in the `option_combinations` table, with foreign keys to the product and options.
3. If you define price rules, new records are created in the `price_rules` table, with foreign keys to the product and options.

## Implementation Details

The process of adding part options is implemented using the following components:

- **AdminPartOptionApiController**: Handles CRUD operations for part options.
- **AdminOptionCombinationApiController**: Handles CRUD operations for option combinations.
- **AdminPriceRuleApiController**: Handles CRUD operations for price rules.

Each controller follows the Actions pattern, delegating business logic to corresponding Action classes. This ensures a clean separation of concerns and makes the code more maintainable.

## Example: Adding a New Rim Color

Let's walk through a concrete example of adding a new rim color to a bicycle product.

### 1. Navigate to the Part Management Page

1. Log in to the admin interface.
2. Click on "Products" in the navigation menu.
3. Find the "Mountain Bike" product and click on the "Parts" button.
4. The system displays the part management page with a list of parts for the Mountain Bike.

### 2. Find the Part to Modify

1. Locate the "Rims" part.
2. Click on the "Options" button for the Rims part.
3. The system displays the option management page with a list of existing rim options (e.g., Black Rims, Silver Rims).

### 3. Create a New Option

1. Click the "Create Option" button.
2. The system displays the option creation form.
3. Enter the option details:
   - **Name**: "Red Rims"
   - **Description**: "Bright red anodized aluminum rims for a striking look."
   - **Base Price**: 150.00
   - **Display Order**: 3
   - **In Stock**: Checked
   - **Active**: Checked
4. Click the "Save" button.
5. The system validates the input and creates the Red Rims option.
6. The system redirects you to the option management page, where the Red Rims option is now listed.

### 4. Update Option Combinations

1. Click on "Option Combinations" in the navigation menu.
2. The system displays the option combination management page.
3. Click the "Create Option Combination" button.
4. The system displays the option combination creation form.
5. Enter the option combination details:
   - **Product**: "Mountain Bike"
   - **If Option**: "Red Rims"
   - **Then Option/Part**: "Red Handlebars"
   - **Rule Type**: "Required"
   - **Description**: "Red rims require red handlebars for a consistent look."
   - **Active**: Checked
6. Click the "Save" button.
7. The system validates the input and creates the option combination.

### 5. Update Price Rules

1. Click on "Price Rules" in the navigation menu.
2. The system displays the price rule management page.
3. Click the "Create Price Rule" button.
4. The system displays the price rule creation form.
5. Enter the price rule details:
   - **Product**: "Mountain Bike"
   - **Primary Option**: "Red Rims"
   - **Dependent Option**: "Red Handlebars"
   - **Price Adjustment**: -50.00
   - **Adjustment Type**: "Fixed"
   - **Description**: "Discount for matching rim and handlebar colors."
   - **Active**: Checked
6. Click the "Save" button.
7. The system validates the input and creates the price rule.

## Best Practices

When adding new part options, consider the following best practices:

1. **Use clear, descriptive names** for options to help customers understand what they're selecting.
2. **Provide detailed descriptions** to help customers make informed decisions.
3. **Set appropriate display orders** to ensure that options are presented in a logical sequence.
4. **Define option combinations carefully** to ensure that customers can only select valid combinations of options.
5. **Set up price rules thoughtfully** to ensure that the pricing is fair and transparent.
6. **Test the product configuration** thoroughly after adding new options to ensure that everything works as expected.