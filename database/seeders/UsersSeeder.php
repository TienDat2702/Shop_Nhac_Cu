<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        for ($i = 0; $i < 10; $i++) {
            User::create([
                'name' => $faker->name,
                'email' => $faker->unique()->safeEmail,
                'password' => Hash::make('password123'), // Mã hóa mật khẩu
                'phone' => $faker->phoneNumber,
                'address' => $faker->address,
                'image' => null,
                'publish' => 2,
                'role_id' => rand(1, 3), // Giả sử bạn có 2 role_id trong bảng roles
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
