<?php

namespace Database\Factories;

use App\Models\Court;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Court>
 */
class CourtFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => 'Court '.fake()->unique()->numberBetween(1, 10),
            'type' => fake()->randomElement(['indoor', 'outdoor', 'covered']),
            'surface' => fake()->randomElement(['cushioned acrylic', 'pro tournament hardcourt', 'modular sport court']),
            'hourly_rate' => fake()->randomElement([20.00, 25.00, 30.00, 35.00]),
            'is_active' => true,
            'description' => fake()->sentence(),
        ];
    }
}
