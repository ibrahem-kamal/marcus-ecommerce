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
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
