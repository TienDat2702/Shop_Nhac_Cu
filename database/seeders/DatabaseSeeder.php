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
        // Seed bảng roles trước
        $this->call(RoleSeeder::class);

        // Sau đó seed bảng users
        $this->call(UserSeeder::class);
    }
}
