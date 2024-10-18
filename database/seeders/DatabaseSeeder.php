<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed bảng roles trước
        $this->call(RoleSeeder::class);

        // Sau đó seed bảng users
        $this->call(UserSeeder::class);
    }
}
