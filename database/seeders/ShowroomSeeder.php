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

        if ($products->isNotEmpty()) {
            foreach ($showrooms as $showroom) {
                $randomProducts = $products->random(min($products->count(), rand(5, 15)));
                foreach ($randomProducts as $product) {
                    $showroom->products()->attach($product->id, ['stock' => $faker->numberBetween(1, 100)]);
                }
            }
        } else {
            $this->command->info('Không tìm thấy sản phẩm. Bỏ qua việc thêm sản phẩm vào showroom.');
        }
    }
}
