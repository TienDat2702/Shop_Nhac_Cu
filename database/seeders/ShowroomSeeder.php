<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Showroom;
use App\Models\Product;
use Faker\Factory as Faker;

class ShowroomSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        for ($i = 0; $i < 10; $i++) {
            Showroom::create([
                'name' => $faker->company,
                'address' => $faker->address,
                'phone' => $faker->phoneNumber,
                'image' => $faker->imageUrl(640, 480, 'business', true),
                'publish' => $faker->boolean(90),
            ]);
        }

        // Thêm sản phẩm vào showroom nếu chưa có
        $showrooms = Showroom::all();
        $products = Product::all();

        foreach ($showrooms as $showroom) {
            $randomProducts = $products->random(rand(10, 30));
            foreach ($randomProducts as $product) {
                if (!$showroom->products->contains($product->id)) {
                    $showroom->products()->attach($product->id, ['stock' => $faker->numberBetween(0, 100)]);
                }
            }
        }
    }
}
