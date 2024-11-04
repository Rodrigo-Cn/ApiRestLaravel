<?php

namespace Database\Factories;

use App\Models\Daily;
use Illuminate\Database\Eloquent\Factories\Factory;

class DailyFactory extends Factory
{
    protected $model = Daily::class;

    public function definition()
    {
        return [
            'reserve_id' => 7,
            'date' => $this->faker->date(),
            'value' => $this->faker->randomFloat(20, 50, 100)
        ];
    }
}
