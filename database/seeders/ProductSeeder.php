<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Brand;
use App\Models\ProductCategory;
use App\Models\Showroom;
use Faker\Factory as Faker;

class ProductSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        $brands = Brand::all();
        $categories = ProductCategory::all();
        $showrooms = Showroom::all();

        for ($i = 0; $i < 50; $i++) {
            $product = Product::create([
                'category_id' => $categories->random()->id,
                'brand_id' => $brands->random()->id,
                'name' => $faker->words(3, true),
                'image' => $faker->imageUrl(640, 480, 'products', true),
                'price' => $faker->randomFloat(2, 100, 1000),
                'price_sale' => $faker->randomFloat(2, 50, min($faker->randomFloat(2, 100, 1000), 900)),
                'view' => $faker->numberBetween(0, 1000),
                'description' => $faker->paragraph(),
                'publish' => $faker->boolean(80),
                'summary' => $faker->sentence(),
            ]);

            // Thêm sản phẩm vào các showroom ngẫu nhiên
            $randomShowrooms = $showrooms->random(rand(1, 3));
            foreach ($randomShowrooms as $showroom) {
                $product->showrooms()->attach($showroom->id, ['stock' => $faker->numberBetween(0, 100)]);
            }
        }
    }
}
