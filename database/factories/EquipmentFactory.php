<?php

namespace Database\Factories;

use App\Models\Equipment;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Equipment>
 */
class EquipmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $category = fake()->randomElement(['paddle', 'balls']);

        return [
            'name' => $category === 'paddle' ? fake()->words(3, true).' Paddle' : fake()->words(2, true).' Balls Pack',
            'category' => $category,
            'price' => $category === 'paddle' ? 5.00 : 3.00,
            'stock' => fake()->numberBetween(10, 30),
            'is_active' => true,
            'description' => fake()->sentence(),
        ];
    }

    public function paddle(): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => 'Pro Carbon Pickleball Paddle',
            'category' => 'paddle',
            'price' => 5.00,
            'stock' => 20,
        ]);
    }

    public function balls(): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => 'Standard Dura Fast 40 Balls (Set of 3)',
            'category' => 'balls',
            'price' => 3.00,
            'stock' => 30,
        ]);
    }
}
