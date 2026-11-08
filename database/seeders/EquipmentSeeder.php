<?php

namespace Database\Seeders;

use App\Models\Equipment;
use Illuminate\Database\Seeder;

class EquipmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $equipment = [
            [
                'name' => 'Pro Carbon Fiber Paddle (Selkirk Vanguard)',
                'category' => 'paddle',
                'price' => 6.00,
                'stock' => 16,
                'is_active' => true,
                'description' => 'Lightweight carbon face with responsive honeycomb core for elite control and spin.',
            ],
            [
                'name' => 'Standard Composite Paddle (Joola Essentials)',
                'category' => 'paddle',
                'price' => 4.00,
                'stock' => 24,
                'is_active' => true,
                'description' => 'Balanced fiberglass paddle, ideal for all skill levels from beginner to intermediate.',
            ],
            [
                'name' => 'Power Grip Elongated Paddle',
                'category' => 'paddle',
                'price' => 5.00,
                'stock' => 12,
                'is_active' => true,
                'description' => 'Extended reach and comfort grip handle for high-velocity drives and deep baseline shots.',
            ],
            [
                'name' => 'Franklin X-40 Outdoor Balls (Set of 3)',
                'category' => 'balls',
                'price' => 3.00,
                'stock' => 30,
                'is_active' => true,
                'description' => 'Official tournament outdoor balls with 40 precisely drilled holes for true flight and durability.',
            ],
            [
                'name' => 'Onix Fuse G2 Indoor Balls (Set of 3)',
                'category' => 'balls',
                'price' => 3.00,
                'stock' => 25,
                'is_active' => true,
                'description' => 'Large-hole indoor balls engineered for consistent bounce on indoor wooden or acrylic courts.',
            ],
        ];

        foreach ($equipment as $item) {
            Equipment::updateOrCreate(['name' => $item['name']], $item);
        }
    }
}
