<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();

        DB::table('users')->insert([
            'name' => 'Newbie Laravel',
            'email' => 'Quynnps32187@fpt.edu.vn',
            'password' => Hash::make('password'),
            'publish' => 1,
            'image' => null,
            'address' => $faker->address,
            'phone' => $faker->phoneNumber,
            'role_id' => 1, // Assumed "Admin" has role_id = 1
            'email_verified_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
