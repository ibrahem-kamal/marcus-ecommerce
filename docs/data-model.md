# Data Model

This document describes the data model for the Marcus E-Commerce Platform.

## Overview

The Marcus E-Commerce Platform uses a relational database model to represent customizable products. The data model is designed to support complex product configurations with multiple parts and options, dynamic pricing based on combinations of options, and rules for valid combinations.

## Entity Relationship Diagram

```
+---------------+       +---------------+       +---------------+
| ProductType   |------>| Part          |------>| PartOption    |
+---------------+       +---------------+       +---------------+
        |                      |                       |
        |                      |                       |
        v                      v                       v
+---------------+       +---------------+       +---------------+
| Cart          |       | OptionCombo   |<------| PriceRule     |
+---------------+       +---------------+       +---------------+
```

## Tables

### ProductType

Represents a type of product that can be configured and purchased.

| Field       | Type         | Description                                |
|-------------|--------------|--------------------------------------------|
| id          | bigint       | Primary key                                |
| name        | string       | Name of the product type (e.g., 'Bicycle') |
| description | text         | Description of the product type            |
| image_path  | string       | Path to the product image                  |
| active      | boolean      | Whether the product is active              |
| timestamps  | timestamps   | Created at and updated at timestamps       |

### Part

Represents a part of a product that can be configured.

| Field         | Type         | Description                                      |
|---------------|--------------|--------------------------------------------------|
| id            | bigint       | Primary key                                      |
| product_type_id | bigint     | Foreign key to ProductType                       |
| name          | string       | Name of the part (e.g., 'Frame Type')            |
| description   | text         | Description of the part                          |
| display_order | integer      | Order in which to display the part in the UI     |
| required      | boolean      | Whether this part must be selected               |
| active        | boolean      | Whether the part is active                       |
| timestamps    | timestamps   | Created at and updated at timestamps             |

### PartOption

Represents an option for a part.

| Field         | Type         | Description                                      |
|---------------|--------------|--------------------------------------------------|
| id            | bigint       | Primary key                                      |
| part_id       | bigint       | Foreign key to Part                              |
| name          | string       | Name of the option (e.g., 'Full-suspension')     |
| description   | text         | Description of the option                        |
| base_price    | decimal      | Base price of this option                        |
| display_order | integer      | Order in which to display the option in the UI   |
| in_stock      | boolean      | Whether this option is in stock                  |
| active        | boolean      | Whether the option is active                     |
| timestamps    | timestamps   | Created at and updated at timestamps             |

### OptionCombination

Defines rules for valid combinations of options.

| Field         | Type         | Description                                      |
|---------------|--------------|--------------------------------------------------|
| id            | bigint       | Primary key                                      |
| product_type_id | bigint     | Foreign key to ProductType                       |
| if_option_id  | bigint       | Foreign key to PartOption (the condition)        |
| then_option_id | bigint      | Foreign key to PartOption (the result)           |
| then_part_id  | bigint       | Foreign key to Part (the result)                 |
| rule_type     | enum         | 'required' or 'prohibited'                       |
| description   | text         | Description of why this rule exists              |
| active        | boolean      | Whether the rule is active                       |
| timestamps    | timestamps   | Created at and updated at timestamps             |

### PriceRule

Defines pricing rules for combinations of options.

| Field             | Type         | Description                                      |
|-------------------|--------------|--------------------------------------------------|
| id                | bigint       | Primary key                                      |
| product_type_id   | bigint       | Foreign key to ProductType                       |
| primary_option_id | bigint       | Foreign key to PartOption (the primary option)   |
| dependent_option_id | bigint     | Foreign key to PartOption (the dependent option) |
| price_adjustment  | decimal      | The price adjustment to apply                    |
| adjustment_type   | enum         | 'fixed' or 'percentage'                          |
| description       | text         | Description of why this price rule exists        |
| active            | boolean      | Whether the rule is active                       |
| timestamps        | timestamps   | Created at and updated at timestamps             |

### Cart

Represents items in a shopping cart.

| Field           | Type         | Description                                      |
|-----------------|--------------|--------------------------------------------------|
| id              | bigint       | Primary key                                      |
| cart_id         | string       | Unique identifier for guest carts                |
| user_id         | bigint       | Foreign key to User (optional)                   |
| product_id      | bigint       | Foreign key to ProductType                       |
| selected_options | json        | JSON representation of selected options          |
| price_adjustments | json       | JSON representation of price adjustments         |
| total_price     | decimal      | Total price of the item                          |
| timestamps      | timestamps   | Created at and updated at timestamps             |

## Relationships

- A **ProductType** has many **Parts**
- A **Part** belongs to a **ProductType** and has many **PartOptions**
- A **PartOption** belongs to a **Part**
- An **OptionCombination** belongs to a **ProductType** and references **PartOptions** and **Parts**
- A **PriceRule** belongs to a **ProductType** and references two **PartOptions**
- A **Cart** references a **ProductType** and optionally a **User**

## Data Flow

1. When a customer views a product, the system loads the **ProductType** with its **Parts** and **PartOptions**.
2. As the customer selects options, the system uses **OptionCombinations** to determine which other options are required or prohibited.
3. The system calculates the price based on the base prices of selected **PartOptions** and any applicable **PriceRules**.
4. When the customer adds the product to their cart, the system creates a **Cart** record with the selected options and calculated price.