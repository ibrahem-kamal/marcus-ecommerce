<?php

namespace Database\Factories;

use App\Models\PartOption;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class PartOptionFactory extends Factory
{
    protected $model = PartOption::class;

    public function definition()
    {
        return [
            'name'=> $this->faker->word(),
            'base_price' => $this->faker->randomFloat(2, 0, 100),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
