# Product Page

This document describes the product page functionality in the Marcus E-Commerce Platform.

## Overview

The product page is where customers configure a product by selecting options for each part. The page dynamically updates to show valid combinations of options and calculates the total price based on the customer's selections.

## User Interface

The product page is implemented in the `Products/Show.vue` component. The UI consists of the following elements:

1. **Product Header**: Displays the product name, description, and a link to go back to the product listing.
2. **Parts and Options**: For each part of the product, displays the available options with their names, descriptions, and prices.
3. **Price Summary**: Shows the base price and any price adjustments, with a total price.
4. **Add to Cart Button**: Allows the customer to add the configured product to their cart.

## Option Selection

Customers select options for each part of the product. The system enforces the following rules:

1. **Required Parts**: Some parts are marked as required, meaning the customer must select an option for that part.
2. **Option Availability**: As the customer selects options, the system updates which other options are available or required based on the option combinations defined in the database.
3. **Auto-Selection**: In some cases, selecting one option will automatically select another option if it's the only valid choice.

## Price Calculation

The system calculates the total price of the configured product as follows:

1. **Base Price**: The sum of the base prices of all selected options.
2. **Price Adjustments**: Any price adjustments defined by price rules for combinations of selected options.
3. **Total Price**: The base price plus all price adjustments.

### Price Calculation Algorithm

```javascript
function calculateTotalPrice(selectedOptions) {
  let basePrice = 0;
  let adjustments = [];
  
  // Calculate base price from selected options
  for (const partId in selectedOptions) {
    const optionId = selectedOptions[partId];
    const option = findOption(partId, optionId);
    basePrice += option.base_price;
  }
  
  // Apply price rules
  for (const rule of priceRules) {
    if (rule.active && 
        selectedOptions[findPartIdForOption(rule.primary_option_id)] === rule.primary_option_id &&
        selectedOptions[findPartIdForOption(rule.dependent_option_id)] === rule.dependent_option_id) {
      
      let adjustment = 0;
      if (rule.adjustment_type === 'fixed') {
        adjustment = rule.price_adjustment;
      } else if (rule.adjustment_type === 'percentage') {
        adjustment = basePrice * (rule.price_adjustment / 100);
      }
      
      adjustments.push({
        description: rule.description || `Adjustment for ${findOptionName(rule.primary_option_id)} + ${findOptionName(rule.dependent_option_id)}`,
        amount: adjustment
      });
    }
  }
  
  // Calculate total price
  const totalPrice = basePrice + adjustments.reduce((sum, adj) => sum + adj.amount, 0);
  
  return {
    basePrice,
    adjustments,
    totalPrice
  };
}
```

## Option Availability Calculation

The system determines which options are available based on the option combinations defined in the database. The algorithm is as follows:

```javascript
function isOptionAvailable(partId, optionId) {
  // If no options are selected yet, all options are available
  if (Object.keys(selectedOptions).length === 0) {
    return true;
  }
  
  // Check if this option is prohibited by any selected option
  for (const selectedPartId in selectedOptions) {
    const selectedOptionId = selectedOptions[selectedPartId];
    
    // Find any rule that prohibits this option when the selected option is chosen
    const prohibitingRule = optionCombinations.find(rule => 
      rule.active &&
      rule.rule_type === 'prohibited' &&
      rule.if_option_id === selectedOptionId &&
      ((rule.then_option_id === optionId) || 
       (rule.then_part_id === partId && rule.then_option_id === null))
    );
    
    if (prohibitingRule) {
      return false;
    }
  }
  
  // Check if this option is required by any selected option
  for (const selectedPartId in selectedOptions) {
    const selectedOptionId = selectedOptions[selectedPartId];
    
    // Find any rule that requires this option when the selected option is chosen
    const requiringRule = optionCombinations.find(rule => 
      rule.active &&
      rule.rule_type === 'required' &&
      rule.if_option_id === selectedOptionId &&
      ((rule.then_option_id === optionId) || 
       (rule.then_part_id === partId && rule.then_option_id === null))
    );
    
    if (requiringRule) {
      return true;
    }
  }
  
  // If no rules apply, the option is available
  return true;
}
```

## Implementation Details

### Data Loading

When the product page loads, it makes an API request to `ProductApiController@show` to retrieve the product data, including:

1. The product details (name, description, etc.)
2. All parts for the product
3. All options for each part
4. All option combinations for the product
5. All price rules for the product

### State Management

The component maintains the following state:

1. `selectedOptions`: An object mapping part IDs to selected option IDs
2. `autoSelectedOptions`: An object tracking which options were automatically selected
3. `price`: An object containing the base price, adjustments, and total price

### Event Handling

The component handles the following events:

1. **Option Selection**: When the customer selects an option, the component:
   - Updates the `selectedOptions` state
   - Recalculates which options are available
   - Auto-selects options if necessary
   - Recalculates the price

2. **Add to Cart**: When the customer clicks the Add to Cart button, the component:
   - Validates that all required parts have selected options
   - Makes an API request to `CartApiController@addToCart` to add the item to the cart
   - Displays a success message and provides a link to view the cart