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
            'reserve_id' => 7,
            'hotel_id' => 7,
            'room_id' => 7,
            'check_in' => $checkIn ,
            'check_out' => $this->faker->dateTimeBetween($checkIn , '+1 year'),
            'total' => 10000,
        ];
    }
}
