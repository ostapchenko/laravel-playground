<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Product::factory()
            ->count(12)
            ->sequence(
                ['name' => 'Mechanical Keyboard'],
                ['name' => 'USB-C Dock'],
                ['name' => 'Noise Cancelling Headphones'],
                ['name' => 'Laptop Stand'],
                ['name' => 'Wireless Mouse'],
                ['name' => 'Webcam'],
                ['name' => 'Desk Lamp'],
                ['name' => 'Monitor Arm'],
                ['name' => 'Portable SSD'],
                ['name' => 'Ergonomic Chair'],
                ['name' => 'Notebook Pack'],
                ['name' => 'Water Bottle'],
            )
            ->create();
    }
}
