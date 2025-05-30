<?php

namespace Database\Factories;

use App\Models\OptionCombination;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class OptionCombinationFactory extends Factory
{
    protected $model = OptionCombination::class;

    public function definition()
    {
        return [
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
