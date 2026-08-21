<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Database\Seeder;

class OrderItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = Product::all();

        Order::doesntHave('items')->get()->each(function (Order $order) use ($products): void {
            $products
                ->random(fake()->numberBetween(1, 4))
                ->each(function (Product $product) use ($order): void {
                    $order->items()->create([
                        'product_id' => $product->id,
                        'quantity' => fake()->numberBetween(1, 5),
                        'unit_price' => fake()->randomFloat(2, 5, 250),
                    ]);
                });
        });
    }
}
