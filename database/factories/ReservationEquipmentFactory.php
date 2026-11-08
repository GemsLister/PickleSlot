<?php

namespace Database\Factories;

use App\Models\Equipment;
use App\Models\Reservation;
use App\Models\ReservationEquipment;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ReservationEquipment>
 */
class ReservationEquipmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'reservation_id' => Reservation::factory(),
            'equipment_id' => Equipment::factory(),
            'quantity' => 2,
            'unit_price' => 5.00,
            'total_price' => 10.00,
        ];
    }
}
