<?php

namespace Database\Seeders;

use App\Models\Court;
use App\Models\Equipment;
use App\Models\Reservation;
use App\Models\ReservationEquipment;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $admin = User::firstOrCreate(
            ['email' => 'admin@pickleslot.com'],
            [
                'password' => Hash::make('password'),
                'is_admin' => true,
            ]
        );

        $player = User::firstOrCreate(
            ['email' => 'player@pickleslot.com'],
            [
                'name' => 'Alex Rivera',
                'phone' => '+1 (555) 014-9982',
                'password' => Hash::make('password'),
                'is_admin' => false,
            ]
        );

        $this->call([
            CourtSeeder::class,
            EquipmentSeeder::class,
        ]);

        $court1 = Court::where('name', 'Court 1 - Grandstand Center')->first();
        $court2 = Court::where('name', 'Court 2 - Championship Blue')->first();
        $paddle = Equipment::where('category', 'paddle')->first();
        $balls = Equipment::where('category', 'balls')->first();

        if ($court1 && $court2 && $paddle && $balls) {
            $tomorrow = now()->addDay()->format('Y-m-d');
            $dayAfter = now()->addDays(2)->format('Y-m-d');

            if (! Reservation::where('user_id', $player->id)->where('court_id', $court1->id)->where('date', $tomorrow)->exists()) {
                $reservation1 = Reservation::create([
                    'user_id' => $player->id,
                    'court_id' => $court1->id,
                    'date' => $tomorrow,
                    'start_time' => '10:00:00',
                    'end_time' => '11:00:00',
                    'court_rate' => $court1->hourly_rate,
                    'court_total' => $court1->hourly_rate,
                    'equipment_total' => ($paddle->price * 2) + $balls->price,
                    'total_price' => $court1->hourly_rate + ($paddle->price * 2) + $balls->price,
                    'status' => 'confirmed',
                    'notes' => 'Looking forward to morning doubles practice!',
                ]);

                ReservationEquipment::create([
                    'reservation_id' => $reservation1->id,
                    'equipment_id' => $paddle->id,
                    'quantity' => 2,
                    'unit_price' => $paddle->price,
                    'total_price' => $paddle->price * 2,
                ]);

                ReservationEquipment::create([
                    'reservation_id' => $reservation1->id,
                    'equipment_id' => $balls->id,
                    'quantity' => 1,
                    'unit_price' => $balls->price,
                    'total_price' => $balls->price,
                ]);
            }

            if (! Reservation::where('user_id', $player->id)->where('court_id', $court2->id)->where('date', $dayAfter)->exists()) {
                Reservation::create([
                    'user_id' => $player->id,
                    'court_id' => $court2->id,
                    'date' => $dayAfter,
                    'start_time' => '14:00:00',
                    'end_time' => '16:00:00',
                    'court_rate' => $court2->hourly_rate,
                    'court_total' => $court2->hourly_rate * 2,
                    'equipment_total' => 0.00,
                    'total_price' => $court2->hourly_rate * 2,
                    'status' => 'confirmed',
                    'notes' => 'Singles ladder tournament match.',
                ]);
            }
        }
    }
}
