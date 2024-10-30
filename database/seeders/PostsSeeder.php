<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Post;
use Faker\Factory as Faker;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class PostsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        for ($i = 0; $i < 50; $i++) {
            $title = $faker->sentence;
            Post::create([
                'publish' => $faker->numberBetween(1, 2), // 0 hoặc 1
                'title' => $title, // Tiêu đề ngẫu nhiên
                'content' => $faker->paragraph, // Nội dung ngẫu nhiên
                'image' => $faker->imageUrl(640, 480, 'nature'), // URL hình ảnh ngẫu nhiên
                'slug' => Str::slug($title),
                'description' => $faker->text(200), // Mô tả ngẫu nhiên
                'deleted_at' => null, // Hoặc có thể sử dụng $faker->dateTime() để tạo ngày giờ xóa
                'created_at' => now(),
                'updated_at' => now(),
                'post_category_id' => $faker->numberBetween(1,100), // Giả sử có từ 1 đến 10 bản ghi trong bảng post_categories
                'user_id' => $faker->numberBetween(1, 20),
            ]);
        }
    }
}
