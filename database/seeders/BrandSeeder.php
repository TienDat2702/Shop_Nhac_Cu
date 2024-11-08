<?php

namespace Database\Seeders;

use App\Models\Brand;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $casio = Brand::create([
            'name' => 'casio',
            'image' => 'casio.jpg',
            'publish' => 1,
            'description' => 'Thương hiệu Casio',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $Roland = Brand::create([
            'name' => 'Roland',
            'image' => 'Roland.jpg',
            'publish' => 1,
            'description' => 'Thương hiệu Roland',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $Suzuki = Brand::create([
            'name' => 'Suzuki',
            'image' => 'Suzuki.jpg',
            'publish' => 1,
            'description' => 'Thương hiệu Suzuki',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $Yamaha = Brand::create([
            'name' => 'Yamaha',
            'image' => 'Yamaha.jpg',
            'publish' => 1,
            'description' => 'Thương hiệu Yamaha',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $Taylor = Brand::create([
            'name' => 'Taylor',
            'image' => 'Taylor.jpg',
            'publish' => 1,
            'description' => 'Thương hiệu Taylor',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $Boston = Brand::create([
            'name' => 'Boston',
            'image' => 'Boston.jpg',
            'publish' => 1,
            'description' => 'Thương hiệu Boston',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $Fender = Brand::create([
            'name' => 'Fender',
            'image' => 'Fender.jpg',
            'publish' => 1,
            'description' => 'Thương hiệu Fender',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $Alesis = Brand::create([
            'name' => 'Alesis',
            'image' => 'Alesis.jpg',
            'publish' => 1,
            'description' => 'Thương hiệu Alesis',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $Steinway_sons = Brand::create([
            'name' => 'Steinway-sons',
            'image' => 'Steinway-sons.jpg',
            'publish' => 1,
            'description' => 'Thương hiệu Steinway-sons',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $Essex = Brand::create([
            'name' => 'Essex',
            'image' => 'Essex.jpg',
            'publish' => 1,
            'description' => 'Thương hiệu Essex',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        
    }
}
