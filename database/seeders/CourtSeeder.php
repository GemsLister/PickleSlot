<?php

namespace Database\Seeders;

use App\Models\Court;
use Illuminate\Database\Seeder;

class CourtSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $courts = [
            [
                'name' => 'Court 1 - Grandstand Center',
                'type' => 'indoor',
                'surface' => 'Pro-Cushion Acrylic',
                'hourly_rate' => 35.00,
                'is_active' => true,
                'description' => 'Premium air-conditioned court with tournament lighting, spectator seating, and video recording setup.',
            ],
            [
                'name' => 'Court 2 - Championship Blue',
                'type' => 'indoor',
                'surface' => 'Pro-Cushion Acrylic',
                'hourly_rate' => 30.00,
                'is_active' => true,
                'description' => 'Climate-controlled championship court with professional shock absorption and electronic scoreboard.',
            ],
            [
                'name' => 'Court 3 - Sunset Outdoor',
                'type' => 'outdoor',
                'surface' => 'Sport Court Modular',
                'hourly_rate' => 22.00,
                'is_active' => true,
                'description' => 'Open-air tournament-grade outdoor court with LED floodlights for night play.',
            ],
            [
                'name' => 'Court 4 - Palm Breeze Outdoor',
                'type' => 'outdoor',
                'surface' => 'Hardcourt Acrylic',
                'hourly_rate' => 20.00,
                'is_active' => true,
                'description' => 'Surrounded by green gardens with shaded player benches and refreshing cooling mist fans.',
            ],
            [
                'name' => 'Court 5 - Covered Pavilion',
                'type' => 'covered',
                'surface' => 'Pro-Cushion Acrylic',
                'hourly_rate' => 28.00,
                'is_active' => true,
                'description' => 'Weatherproof canopy roof that protects from rain and intense midday sun with gentle cross-breeze.',
            ],
        ];

        foreach ($courts as $court) {
            Court::updateOrCreate(['name' => $court['name']], $court);
        }
    }
}
