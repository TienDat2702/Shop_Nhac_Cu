<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
// use Illuminate\Database\Seeder;

// class ProductCategorySeeder extends Seeder
// {
//     /**
//      * Run the database seeds.
//      */
//     public function run(): void
//     {
//         //
//     }
// }
use Illuminate\Database\Seeder;
use App\Models\ProductCategory;

class ProductCategorySeeder extends Seeder
{
    public function run()
    {
        // Tạo danh mục chính
        $guitar = ProductCategory::create([
            'image' => 'guitar.jpg',
            'summary' => 'Tất cả các loại guitar',
            'publish' => 1,
            'parent_id' => 0,
            'description' => 'Danh mục các loại guitar khác nhau',
        ]);

        $piano = ProductCategory::create([
            'image' => 'piano.jpg',
            'summary' => 'Tất cả các loại piano',
            'publish' => 1,
            'parent_id' => 0,
            'description' => 'Danh mục các loại piano khác nhau',
        ]);

        // Tạo các danh mục con cho Guitar
        ProductCategory::create([
            'image' => 'acoustic_guitar.jpg',
            'summary' => 'Guitar Acoustic',
            'publish' => 1,
            'parent_id' => $guitar->id,
            'description' => 'Guitar acoustic dành cho mọi đối tượng',
        ]);

        ProductCategory::create([
            'image' => 'classic_guitar.jpg',
            'summary' => 'Guitar Classic',
            'publish' => 1,
            'parent_id' => $guitar->id,
            'description' => 'Guitar cổ điển với âm thanh sâu lắng',
        ]);

        // Tạo các danh mục con cho Piano
        ProductCategory::create([
            'image' => 'electric_piano.jpg',
            'summary' => 'Piano điện',
            'publish' => 1,
            'parent_id' => $piano->id,
            'description' => 'Piano điện tử với âm thanh hiện đại',
        ]);

        ProductCategory::create([
            'image' => 'grand_piano.jpg',
            'summary' => 'Piano Grand',
            'publish' => 1,
            'parent_id' => $piano->id,
            'description' => 'Piano Grand với âm thanh tuyệt hảo',
        ]);
    }
}
