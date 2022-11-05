<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Travel>
 */
class TravelFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'price' => $price = rand(6000, 69999) / 10,
            'departure_time' => fake()->dateTimeBetween('now', '6 days'),
            'arrival_time' => fake()->dateTimeBetween('7 days', '14days'),
            'departure_place' => fake()->city(),
            'arrival_place' => fake()->city(),
            'total_passengers' => $total_passengers = intval($price / rand(100, 200)),
            'available_passengers' => $total_passengers,
        ];
    }
}
