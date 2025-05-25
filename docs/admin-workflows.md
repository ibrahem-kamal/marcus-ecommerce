# Administrative Workflows

This document describes the main administrative workflows for managing the Marcus E-Commerce Platform.

## Overview

The administrative interface allows Marcus (or other administrators) to manage all aspects of the e-commerce platform, including products, parts, options, option combinations, and price rules. The interface is designed to be intuitive and efficient, with a focus on the most common tasks.

## Authentication

Administrators must authenticate to access the administrative interface. The authentication workflow is as follows:

1. Administrator navigates to the admin login page (`/admin/login`).
2. Administrator enters their credentials (email and password).
3. The system validates the credentials and creates a session.
4. The system redirects the administrator to the admin dashboard.

## Dashboard

The admin dashboard provides an overview of the platform, including:

- Total number of products
- Total number of parts
- Total number of options
- Recent activity

From the dashboard, administrators can navigate to the various management interfaces.

## Product Management

Administrators can manage products through the product management interface. The main workflows are:

### Viewing Products

1. Administrator navigates to the product management page.
2. The system displays a list of all products, with their names, descriptions, and status.
3. Administrator can filter and sort the list to find specific products.

### Creating a Product

1. Administrator clicks the "Create Product" button.
2. The system displays a form for creating a new product.
3. Administrator enters the product details:
   - Name
   - Description
   - Image (optional)
   - Active status
4. Administrator clicks the "Save" button.
5. The system validates the input and creates the product.
6. The system redirects the administrator to the product management page.

### Editing a Product

1. Administrator clicks the "Edit" button for a product.
2. The system displays a form with the product details.
3. Administrator updates the product details.
4. Administrator clicks the "Save" button.
5. The system validates the input and updates the product.
6. The system redirects the administrator to the product management page.

### Deleting a Product

1. Administrator clicks the "Delete" button for a product.
2. The system displays a confirmation dialog.
3. Administrator confirms the deletion.
4. The system deletes the product and all associated parts, options, option combinations, and price rules.
5. The system redirects the administrator to the product management page.

## Part Management

Administrators can manage parts for a product through the part management interface. The main workflows are:

### Viewing Parts

1. Administrator navigates to the part management page for a product.
2. The system displays a list of all parts for the product, with their names, descriptions, and status.
3. Administrator can filter and sort the list to find specific parts.

### Creating a Part

1. Administrator clicks the "Create Part" button.
2. The system displays a form for creating a new part.
3. Administrator enters the part details:
   - Name
   - Description
   - Display order
   - Required status
   - Active status
4. Administrator clicks the "Save" button.
5. The system validates the input and creates the part.
6. The system redirects the administrator to the part management page.

### Editing a Part

1. Administrator clicks the "Edit" button for a part.
2. The system displays a form with the part details.
3. Administrator updates the part details.
4. Administrator clicks the "Save" button.
5. The system validates the input and updates the part.
6. The system redirects the administrator to the part management page.

### Deleting a Part

1. Administrator clicks the "Delete" button for a part.
2. The system displays a confirmation dialog.
3. Administrator confirms the deletion.
4. The system deletes the part and all associated options, option combinations, and price rules.
5. The system redirects the administrator to the part management page.

## Option Management

Administrators can manage options for a part through the option management interface. The main workflows are:

### Viewing Options

1. Administrator navigates to the option management page for a part.
2. The system displays a list of all options for the part, with their names, descriptions, prices, and status.
3. Administrator can filter and sort the list to find specific options.

### Creating an Option

1. Administrator clicks the "Create Option" button.
2. The system displays a form for creating a new option.
3. Administrator enters the option details:
   - Name
   - Description
   - Base price
   - Display order
   - In stock status
   - Active status
4. Administrator clicks the "Save" button.
5. The system validates the input and creates the option.
6. The system redirects the administrator to the option management page.

### Editing an Option

1. Administrator clicks the "Edit" button for an option.
2. The system displays a form with the option details.
3. Administrator updates the option details.
4. Administrator clicks the "Save" button.
5. The system validates the input and updates the option.
6. The system redirects the administrator to the option management page.

### Deleting an Option

1. Administrator clicks the "Delete" button for an option.
2. The system displays a confirmation dialog.
3. Administrator confirms the deletion.
4. The system deletes the option and all associated option combinations and price rules.
5. The system redirects the administrator to the option management page.

## Option Combination Management

Administrators can manage option combinations through the option combination management interface. The main workflows are:

### Viewing Option Combinations

1. Administrator navigates to the option combination management page for a product.
2. The system displays a list of all option combinations for the product, with their conditions, results, and status.
3. Administrator can filter and sort the list to find specific option combinations.

### Creating an Option Combination

1. Administrator clicks the "Create Option Combination" button.
2. The system displays a form for creating a new option combination.
3. Administrator enters the option combination details:
   - If option (the condition)
   - Then option or part (the result)
   - Rule type (required or prohibited)
   - Description
   - Active status
4. Administrator clicks the "Save" button.
5. The system validates the input and creates the option combination.
6. The system redirects the administrator to the option combination management page.

### Editing an Option Combination

1. Administrator clicks the "Edit" button for an option combination.
2. The system displays a form with the option combination details.
3. Administrator updates the option combination details.
4. Administrator clicks the "Save" button.
5. The system validates the input and updates the option combination.
6. The system redirects the administrator to the option combination management page.

### Deleting an Option Combination

1. Administrator clicks the "Delete" button for an option combination.
2. The system displays a confirmation dialog.
3. Administrator confirms the deletion.
4. The system deletes the option combination.
5. The system redirects the administrator to the option combination management page.

## Price Rule Management

Administrators can manage price rules through the price rule management interface. The main workflows are:

### Viewing Price Rules

1. Administrator navigates to the price rule management page for a product.
2. The system displays a list of all price rules for the product, with their conditions, adjustments, and status.
3. Administrator can filter and sort the list to find specific price rules.

### Creating a Price Rule

1. Administrator clicks the "Create Price Rule" button.
2. The system displays a form for creating a new price rule.
3. Administrator enters the price rule details:
   - Primary option
   - Dependent option
   - Price adjustment
   - Adjustment type (fixed or percentage)
   - Description
   - Active status
4. Administrator clicks the "Save" button.
5. The system validates the input and creates the price rule.
6. The system redirects the administrator to the price rule management page.

### Editing a Price Rule

1. Administrator clicks the "Edit" button for a price rule.
2. The system displays a form with the price rule details.
3. Administrator updates the price rule details.
4. Administrator clicks the "Save" button.
5. The system validates the input and updates the price rule.
6. The system redirects the administrator to the price rule management page.

### Deleting a Price Rule

1. Administrator clicks the "Delete" button for a price rule.
2. The system displays a confirmation dialog.
3. Administrator confirms the deletion.
4. The system deletes the price rule.
5. The system redirects the administrator to the price rule management page.

## Implementation Details

The administrative workflows are implemented using the following components:

- **AdminAuthApiController**: Handles administrator authentication.
- **AdminDashboardApiController**: Provides data for the admin dashboard.
- **AdminProductApiController**: Handles CRUD operations for products.
- **AdminPartApiController**: Handles CRUD operations for parts.
- **AdminPartOptionApiController**: Handles CRUD operations for part options.
- **AdminOptionCombinationApiController**: Handles CRUD operations for option combinations.
- **AdminPriceRuleApiController**: Handles CRUD operations for price rules.

Each controller follows the Actions pattern, delegating business logic to corresponding Action classes. This ensures a clean separation of concerns and makes the code more maintainable.