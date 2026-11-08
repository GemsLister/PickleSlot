<?php

namespace Database\Factories;

use App\Models\Court;
use App\Models\Reservation;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Reservation>
 */
class ReservationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $date = fake()->dateTimeBetween('+1 day', '+10 days')->format('Y-m-d');
        $hour = fake()->numberBetween(8, 20);
        $startTime = sprintf('%02d:00', $hour);
        $endTime = sprintf('%02d:00', $hour + 1);

        return [
            'user_id' => User::factory(),
            'court_id' => Court::factory(),
            'date' => $date,
            'start_time' => $startTime,
            'end_time' => $endTime,
            'court_rate' => 25.00,
            'court_total' => 25.00,
            'equipment_total' => 0.00,
            'total_price' => 25.00,
            'status' => 'confirmed',
            'notes' => fake()->optional()->sentence(),
        ];
    }
}
