# New Product Creation

This document describes the process of creating a new product in the Marcus E-Commerce Platform.

## Overview

Creating a new product in the Marcus E-Commerce Platform involves several steps:

1. Creating the product type
2. Adding parts to the product
3. Adding options to each part
4. Defining option combinations
5. Setting up price rules

This document focuses on the first step: creating the product type. For information on the other steps, see the [Adding Part Choices](adding-part-choices.md) and [Setting Prices](setting-prices.md) documentation.

## Required Information

To create a new product, you need the following information:

- **Name**: A descriptive name for the product (e.g., "Mountain Bike", "Road Bike", "Hybrid Bike").
- **Description**: A detailed description of the product, including its features and benefits.
- **Image** (optional): An image of the product to display on the product listing page.

## Step-by-Step Process

### 1. Navigate to the Product Management Page

1. Log in to the admin interface.
2. Click on "Products" in the navigation menu.
3. The system displays the product management page with a list of existing products.

### 2. Create a New Product

1. Click the "Create Product" button.
2. The system displays the product creation form.
3. Enter the product details:
   - **Name**: Enter a descriptive name for the product.
   - **Description**: Enter a detailed description of the product.
   - **Image** (optional): Upload an image of the product.
   - **Active**: Check this box to make the product visible to customers.
4. Click the "Save" button.
5. The system validates the input and creates the product.
6. The system redirects you to the product management page, where the new product is now listed.

### 3. Add Parts to the Product

After creating the product, you need to add parts to it. Parts represent the configurable aspects of the product (e.g., frame, wheels, handlebars).

1. Click on the "Parts" button for the newly created product.
2. The system displays the part management page for the product.
3. Click the "Create Part" button.
4. The system displays the part creation form.
5. Enter the part details:
   - **Name**: Enter a descriptive name for the part (e.g., "Frame", "Wheels", "Handlebars").
   - **Description**: Enter a detailed description of the part.
   - **Display Order**: Enter a number to control the order in which the part appears in the UI.
   - **Required**: Check this box if the customer must select an option for this part.
   - **Active**: Check this box to make the part visible to customers.
6. Click the "Save" button.
7. The system validates the input and creates the part.
8. Repeat steps 3-7 for each part of the product.

### 4. Add Options to Each Part

After adding parts to the product, you need to add options to each part. Options represent the choices available for each part (e.g., aluminum frame, carbon frame, steel frame).

1. Click on the "Options" button for a part.
2. The system displays the option management page for the part.
3. Click the "Create Option" button.
4. The system displays the option creation form.
5. Enter the option details:
   - **Name**: Enter a descriptive name for the option (e.g., "Aluminum Frame", "Carbon Frame", "Steel Frame").
   - **Description**: Enter a detailed description of the option.
   - **Base Price**: Enter the base price of the option.
   - **Display Order**: Enter a number to control the order in which the option appears in the UI.
   - **In Stock**: Check this box if the option is in stock.
   - **Active**: Check this box to make the option visible to customers.
6. Click the "Save" button.
7. The system validates the input and creates the option.
8. Repeat steps 3-7 for each option of the part.
9. Repeat steps 1-8 for each part of the product.

### 5. Define Option Combinations

After adding options to each part, you need to define option combinations. Option combinations define rules for valid combinations of options (e.g., if the customer selects a carbon frame, they must also select carbon handlebars).

1. Click on "Option Combinations" in the navigation menu.
2. The system displays the option combination management page.
3. Click the "Create Option Combination" button.
4. The system displays the option combination creation form.
5. Enter the option combination details:
   - **Product**: Select the product for which you're defining the option combination.
   - **If Option**: Select the option that triggers the rule.
   - **Then Option/Part**: Select the option or part that is affected by the rule.
   - **Rule Type**: Select "Required" if the then option/part is required when the if option is selected, or "Prohibited" if the then option/part is prohibited when the if option is selected.
   - **Description**: Enter a description of the rule to help customers understand why certain combinations are required or prohibited.
   - **Active**: Check this box to make the rule active.
6. Click the "Save" button.
7. The system validates the input and creates the option combination.
8. Repeat steps 3-7 for each option combination you want to define.

### 6. Set Up Price Rules

After defining option combinations, you need to set up price rules. Price rules define how the price of the product changes based on the options selected (e.g., if the customer selects both a carbon frame and carbon handlebars, they get a discount).

1. Click on "Price Rules" in the navigation menu.
2. The system displays the price rule management page.
3. Click the "Create Price Rule" button.
4. The system displays the price rule creation form.
5. Enter the price rule details:
   - **Product**: Select the product for which you're defining the price rule.
   - **Primary Option**: Select the primary option that triggers the price adjustment.
   - **Dependent Option**: Select the dependent option that, when selected along with the primary option, triggers the price adjustment.
   - **Price Adjustment**: Enter the amount of the price adjustment.
   - **Adjustment Type**: Select "Fixed" if the adjustment is a fixed amount, or "Percentage" if the adjustment is a percentage of the base price.
   - **Description**: Enter a description of the price rule to help customers understand why the price is adjusted.
   - **Active**: Check this box to make the rule active.
6. Click the "Save" button.
7. The system validates the input and creates the price rule.
8. Repeat steps 3-7 for each price rule you want to define.

## Database Changes

When you create a new product, the following changes are made to the database:

1. A new record is created in the `product_types` table with the product details.
2. When you add parts to the product, new records are created in the `parts` table, with foreign keys to the product.
3. When you add options to a part, new records are created in the `part_options` table, with foreign keys to the part.
4. When you define option combinations, new records are created in the `option_combinations` table, with foreign keys to the product and options.
5. When you set up price rules, new records are created in the `price_rules` table, with foreign keys to the product and options.

## Implementation Details

The product creation process is implemented using the following components:

- **AdminProductApiController**: Handles CRUD operations for products.
- **AdminPartApiController**: Handles CRUD operations for parts.
- **AdminPartOptionApiController**: Handles CRUD operations for part options.
- **AdminOptionCombinationApiController**: Handles CRUD operations for option combinations.
- **AdminPriceRuleApiController**: Handles CRUD operations for price rules.

Each controller follows the Actions pattern, delegating business logic to corresponding Action classes. This ensures a clean separation of concerns and makes the code more maintainable.

## Best Practices

When creating a new product, consider the following best practices:

1. **Use clear, descriptive names** for products, parts, and options to help customers understand what they're selecting.
2. **Provide detailed descriptions** to help customers make informed decisions.
3. **Set appropriate display orders** to ensure that parts and options are presented in a logical sequence.
4. **Define option combinations carefully** to ensure that customers can only select valid combinations of options.
5. **Set up price rules thoughtfully** to ensure that the pricing is fair and transparent.
6. **Test the product configuration** thoroughly before making it available to customers.