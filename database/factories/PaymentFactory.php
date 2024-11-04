<?php

namespace Database\Factories;

use App\Models\Payment;
use Illuminate\Database\Eloquent\Factories\Factory;

class PaymentFactory extends Factory
{
    protected $model = Payment::class;

    public function definition()
    {
        return [
            'reserve_id' => 7,
            'method' => $this->faker->numberBetween(1, 3),
            'value' => $this->faker->randomFloat(20, 50, 100)
        ];
    }
}
