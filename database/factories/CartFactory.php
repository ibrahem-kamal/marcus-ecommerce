<?php

namespace Database\Factories;

use App\Models\Cart;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class CartFactory extends Factory
{
    protected $model = Cart::class;

    public function definition(): array
    {
        return [
            'cart_id' => $this->faker->uuid,
            'user_id' => null, // Default to guest cart
            'product_id' => \App\Models\ProductType::factory(),
            'selected_options' => [
                'options' => [
                    '1' => [
                        'partId' => 1,
                        'partName' => 'Part 1',
                        'optionId' => 1,
                        'optionName' => 'Option 1',
                        'price' => 100.00
                    ]
                ]
            ],
            'price_adjustments' => [
                [
                    'description' => 'Adjustment 1',
                    'amount' => 10.00
                ]
            ],
            'total_price' => 110.00,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }

    /**
     * Indicate that the cart belongs to a user.
     */
    public function forUser(\App\Models\User $user): self
    {
        return $this->state(function (array $attributes) use ($user) {
            return [
                'user_id' => $user->id,
            ];
        });
    }
}
