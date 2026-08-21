<?php

namespace Database\Seeders;

use App\Enums\OrderStatus;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        $customers = Customer::all();
        $products = Product::all();

        $customers->each(function (Customer $customer) use ($products): void {
            Order::factory()
                ->count(fake()->numberBetween(1, 5))
                ->for($customer)
                ->state(fn (): array => [
                    'status' => fake()->randomElement(OrderStatus::cases()),
                    'created_at' => fake()->dateTimeBetween('-90 days', 'now'),
                ])
                ->create()
                ->each(function (Order $order) use ($products): void {
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
        });
    }
}
