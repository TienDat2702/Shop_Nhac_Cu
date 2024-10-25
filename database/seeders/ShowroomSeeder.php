<?php

namespace Database\Seeders;

<<<<<<< HEAD
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class ShowroomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('showrooms')->insert([
            [
                'name' => 'Showroom 1',
                'address' => '123 Main Street, City 1',
                'phone' => '0123456789',
                'image' => 'showroom1.jpg',
                'publish' => 1,
                'deleted_at' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Showroom 2',
                'address' => '456 Another Street, City 2',
                'phone' => '0987654321',
                'image' => 'showroom2.jpg',
                'publish' => 1,
                'deleted_at' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Showroom 3',
                'address' => '789 Third Street, City 3',
                'phone' => '0123456780',
                'image' => 'showroom3.jpg',
                'publish' => 1,
                'deleted_at' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
=======
use Illuminate\Database\Seeder;
use App\Models\Showroom;
use App\Models\Product;
use Faker\Factory as Faker;

class ShowroomSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        for ($i = 0; $i < 10; $i++) {
            Showroom::create([
                'name' => $faker->company,
                'address' => $faker->address,
                'phone' => $faker->phoneNumber,
                'image' => $faker->imageUrl(640, 480, 'business', true),
                'publish' => $faker->boolean(90),
            ]);
        }

        // Thêm sản phẩm vào showroom nếu chưa có
        $showrooms = Showroom::all();
        $products = Product::all();

        if ($products->isNotEmpty()) {
            foreach ($showrooms as $showroom) {
                $randomProducts = $products->random(min($products->count(), rand(5, 15)));
                foreach ($randomProducts as $product) {
                    $showroom->products()->attach($product->id, ['stock' => $faker->numberBetween(1, 100)]);
                }
            }
        } else {
            $this->command->info('Không tìm thấy sản phẩm. Bỏ qua việc thêm sản phẩm vào showroom.');
        }
>>>>>>> origin/Dat
    }
}
