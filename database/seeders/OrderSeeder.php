<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Customer;
use App\Models\Product;
use Faker\Factory as Faker;

class OrderSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();
        $customers = Customer::all();
        $products = Product::all();

        for ($i = 0; $i < 50; $i++) {
            $customer = $customers->random();
            $order = Order::create([
                'customer_id' => $customer->id,
                'discount_id' => null,
                'name' => $customer->name,
                'email' => $customer->email,
                'status' => $faker->randomElement(['chờ duyệt', 'đang giao', 'đã giao']),
                'customer_note' => $faker->optional()->sentence,
                'user_note' => $faker->optional()->sentence,
                'address' => $faker->address,
                'phone' => $customer->phone,
            ]);

            $numberOfProducts = $faker->numberBetween(1, 5);
            $orderProducts = $products->random($numberOfProducts);

            foreach ($orderProducts as $product) {
                OrderDetail::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => $faker->numberBetween(1, 5),
                    'price' => $product->price,
                ]);
            }
        }
    }
}
