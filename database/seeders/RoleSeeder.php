<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        Role::create(['name' => 'Admin', 'description' => 'Quản trị viên']);
        Role::create(['name' => 'User', 'description' => 'Người dùng']);
        Role::create(['name' => 'Manager', 'description' => 'Quản lý']);
    }
}