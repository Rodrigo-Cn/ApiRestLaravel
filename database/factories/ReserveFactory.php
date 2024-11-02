<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Reserve;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Reserve>
 */
class ReserveFactory extends Factory
{
    protected $model = Reserve::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $checkIn = $this->faker->dateTimeBetween('now', '+1 year');

        return [
            'hotel_id' => $this->faker->numberBetween(1, 3),
            'room_id' => $this->faker->numberBetween(1, 6),
            'check_in' => $checkIn ,
            'check_out' => $this->faker->dateTimeBetween($checkIn , '+3 year'),
            'total' => 0,
        ];
    }
}
