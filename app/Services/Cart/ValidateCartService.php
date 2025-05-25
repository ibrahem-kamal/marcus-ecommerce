<?php

namespace App\Services\Cart;

use App\Models\ProductType;
use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;

class ValidateCartService
{
    /**
     * Validate the cart data.
     *
     * @param array $data The cart data to validate
     * @return array The validated cart data
     * @throws ValidationException If the cart data is invalid
     */
    public function validate(array $data): array
    {
        // Run the data through each validation pipeline
        $data = $this->validateProduct($data);
        $data = $this->validateSelectedOptions($data);
        $data = $this->validateOptionCombinations($data);
        $data = $this->validatePriceCalculations($data);

        return $data;
    }

    /**
     * Validate that the product exists and is active.
     *
     * @param array $data The cart data
     * @return array The validated cart data
     * @throws ValidationException If the product is invalid
     */
    protected function validateProduct(array $data): array
    {
        $product = ProductType::query()
            ->where('id', $data['product_id'])
            ->active()
            ->first();

        if (!$product) {
            throw ValidationException::withMessages([
                'product_id' => 'The selected product is invalid or inactive.',
            ]);
        }

        // Load the product relationships needed for validation
        $product->load([
            'parts' => function ($query) {
                $query->active()
                    ->orderBy('display_order');
            },
            'parts.options' => function ($query) {
                $query->active()
                    ->inStock()
                    ->orderBy('display_order');
            },
            'optionCombinations' => function ($query) {
                $query->active();
            },
            'priceRules' => function ($query) {
                $query->active();
            }
        ]);

        // Add the loaded product to the data for use in subsequent validations
        $data['_product'] = $product;

        return $data;
    }

    /**
     * Validate that the selected options are valid for the product.
     *
     * @param array $data The cart data
     * @return array The validated cart data
     * @throws ValidationException If the selected options are invalid
     */
    protected function validateSelectedOptions(array $data): array
    {
        $product = $data['_product'];
        $selectedOptions = $data['selected_options']['options'] ?? [];

        // Check that all required parts have options selected
        $requiredParts = $product->parts->where('required', true);
        foreach ($requiredParts as $part) {
            if (!isset($selectedOptions[$part->id])) {
                throw ValidationException::withMessages([
                    'selected_options' => "The required part '{$part->name}' does not have a selected option.",
                ]);
            }
        }

        // Check that all selected options belong to the product
        foreach ($selectedOptions as $partId => $option) {
            $part = $product->parts->firstWhere('id', $option['partId']);
            if (!$part) {
                throw ValidationException::withMessages([
                    'selected_options' => "The part with ID {$option['partId']} does not belong to the selected product.",
                ]);
            }

            $partOption = $part->options->firstWhere('id', $option['optionId']);
            if (!$partOption) {
                throw ValidationException::withMessages([
                    'selected_options' => "The option with ID {$option['optionId']} does not belong to the part '{$part->name}'.",
                ]);
            }

            // Validate the option price
            if ($partOption->base_price != $option['price']) {
                throw ValidationException::withMessages([
                    'selected_options' => "The price for option '{$partOption->name}' is incorrect.",
                ]);
            }
        }

        return $data;
    }

    /**
     * Validate that the selected options comply with option combinations.
     *
     * @param array $data The cart data
     * @return array The validated cart data
     * @throws ValidationException If the option combinations are invalid
     */
    protected function validateOptionCombinations(array $data): array
    {
        $product = $data['_product'];
        $selectedOptions = $data['selected_options']['options'] ?? [];
        $optionCombinations = $product->optionCombinations;

        // Check required combinations
        $requiredCombinations = $optionCombinations->where('rule_type', 'required');
        foreach ($requiredCombinations as $combo) {
            $ifOptionId = $combo->if_option_id;
            $thenOptionId = $combo->then_option_id;
            $thenPartId = $combo->then_part_id;

            // Find the part ID for the 'if' option
            $ifPartId = null;
            foreach ($product->parts as $part) {
                if ($part->options->contains('id', $ifOptionId)) {
                    $ifPartId = $part->id;
                    break;
                }
            }

            // If the 'if' option is selected, then the 'then' option must also be selected
            if ($ifPartId && isset($selectedOptions[$ifPartId]) && $selectedOptions[$ifPartId]['optionId'] == $ifOptionId) {
                if (!isset($selectedOptions[$thenPartId]) || $selectedOptions[$thenPartId]['optionId'] != $thenOptionId) {
                    $ifOptionName = $product->parts->firstWhere('id', $ifPartId)->options->firstWhere('id', $ifOptionId)->name;
                    $thenOptionName = $product->parts->firstWhere('id', $thenPartId)->options->firstWhere('id', $thenOptionId)->name;
                    
                    throw ValidationException::withMessages([
                        'selected_options' => "When '{$ifOptionName}' is selected, '{$thenOptionName}' must also be selected.",
                    ]);
                }
            }
        }

        // Check prohibited combinations
        $prohibitedCombinations = $optionCombinations->where('rule_type', 'prohibited');
        foreach ($prohibitedCombinations as $combo) {
            $ifOptionId = $combo->if_option_id;
            $thenOptionId = $combo->then_option_id;
            $thenPartId = $combo->then_part_id;

            // Find the part ID for the 'if' option
            $ifPartId = null;
            foreach ($product->parts as $part) {
                if ($part->options->contains('id', $ifOptionId)) {
                    $ifPartId = $part->id;
                    break;
                }
            }

            // If the 'if' option is selected, then the 'then' option must not be selected
            if ($ifPartId && isset($selectedOptions[$ifPartId]) && $selectedOptions[$ifPartId]['optionId'] == $ifOptionId) {
                if (isset($selectedOptions[$thenPartId]) && $selectedOptions[$thenPartId]['optionId'] == $thenOptionId) {
                    $ifOptionName = $product->parts->firstWhere('id', $ifPartId)->options->firstWhere('id', $ifOptionId)->name;
                    $thenOptionName = $product->parts->firstWhere('id', $thenPartId)->options->firstWhere('id', $thenOptionId)->name;
                    
                    throw ValidationException::withMessages([
                        'selected_options' => "When '{$ifOptionName}' is selected, '{$thenOptionName}' cannot be selected.",
                    ]);
                }
            }
        }

        return $data;
    }

    /**
     * Validate that the price calculations are correct.
     *
     * @param array $data The cart data
     * @return array The validated cart data
     * @throws ValidationException If the price calculations are invalid
     */
    protected function validatePriceCalculations(array $data): array
    {
        $product = $data['_product'];
        $selectedOptions = $data['selected_options']['options'] ?? [];
        $priceAdjustments = $data['selected_options']['price_adjustments'] ?? [];
        $totalPrice = $data['total_price'];

        // Calculate the base price from selected options
        $basePrice = 0;
        foreach ($selectedOptions as $partId => $option) {
            $basePrice += $option['price'];
        }

        // Calculate price adjustments based on price rules
        $calculatedAdjustments = [];
        foreach ($product->priceRules as $rule) {
            $primaryOptionId = $rule->primary_option_id;
            $dependentOptionId = $rule->dependent_option_id;

            // Find the part IDs for the primary and dependent options
            $primaryPartId = null;
            $dependentPartId = null;
            foreach ($product->parts as $part) {
                if ($part->options->contains('id', $primaryOptionId)) {
                    $primaryPartId = $part->id;
                }
                if ($part->options->contains('id', $dependentOptionId)) {
                    $dependentPartId = $part->id;
                }
            }

            // If both options are selected, apply the price adjustment
            if ($primaryPartId && $dependentPartId &&
                isset($selectedOptions[$primaryPartId]) && $selectedOptions[$primaryPartId]['optionId'] == $primaryOptionId &&
                isset($selectedOptions[$dependentPartId]) && $selectedOptions[$dependentPartId]['optionId'] == $dependentOptionId) {
                
                $amount = (float) $rule->price_adjustment;
                $description = $rule->description ?: "Price adjustment for option combination";
                
                $calculatedAdjustments[] = [
                    'description' => $description,
                    'amount' => $amount
                ];
            }
        }

        // Calculate the total price
        $calculatedTotalPrice = $basePrice;
        foreach ($calculatedAdjustments as $adjustment) {
            $calculatedTotalPrice += $adjustment['amount'];
        }

        // Validate the price adjustments
        if (count($priceAdjustments) !== count($calculatedAdjustments)) {
            throw ValidationException::withMessages([
                'price_adjustments' => 'The price adjustments are incorrect.',
            ]);
        }

        // Validate the total price (allow for small floating-point differences)
        if (abs($calculatedTotalPrice - $totalPrice) > 0.01) {
            throw ValidationException::withMessages([
                'total_price' => 'The total price is incorrect.',
            ]);
        }

        return $data;
    }
}