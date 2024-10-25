<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
<<<<<<< HEAD
        // Tạo một người dùng mẫu
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
=======
        $this->call([
            BrandSeeder::class,
            ProductCategorySeeder::class,
            ShowroomSeeder::class,
            ProductSeeder::class,
            LoyaltyLevelSeeder::class,
            CustomerSeeder::class,
            OrderSeeder::class,
>>>>>>> origin/Dat
        ]);

        // Gọi ShowroomSeeder
        $this->call(ShowroomSeeder::class);
    }
}
