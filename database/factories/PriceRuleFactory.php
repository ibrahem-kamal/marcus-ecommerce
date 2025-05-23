<?php

namespace Database\Factories;

use App\Models\PriceRule;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class PriceRuleFactory extends Factory
{
    protected $model = PriceRule::class;

    public function definition()
    {
        return [
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
