<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            BrandSeeder::class,
            ProductCategorySeeder::class,
            ShowroomSeeder::class,
            ProductSeeder::class,
            LoyaltyLevelSeeder::class,
            CustomerSeeder::class,
            OrderSeeder::class,
        ]);

        // Gọi ShowroomSeeder
        $this->call(ShowroomSeeder::class);
    }
}
