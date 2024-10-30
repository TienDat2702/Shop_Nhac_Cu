<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Brand;
use App\Models\ProductCategory;
use Faker\Factory as Faker;

class ProductSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        $brands = Brand::all();
        $categories = ProductCategory::all();

        // Dữ liệu từ db.json
        $productsData = [
            [
                "category_id"=> 5,
                "name"=> "KAWAI ND-21",
                "image"=> "kawai-nd-21-dep-sang-trong-viet-thuong-music-450x471.jpg",
                "price"=> 99900000,
                "price_sale"=> 93000000,
                "short_description"=> "Đàn piano Kawai ND-21 sở hữu chất lượng âm thanh mạnh mẽ, trong trẻo và cấu trúc bền vững. Đây là sự lựa chọn tuyệt vời cho người mới bắt đầu hoặc các nhạc sĩ chuyên nghiệp."
            ],
            [
                "category_id"=> 5,
                "name"=> "KAWAI K-15E",
                "image"=> "piano-kawai-k15e-450x471.jpg",
                "price"=> 91400000,
                "price_sale"=> 82000000,
                "short_description"=> "Đàn piano Kawai K-15E, một sản phẩm xuất sắc giúp người dùng tiếp cận dòng K-Series cao cấp. Thiết kế tinh tế, âm thanh chuẩn xác."
            ],
            [
                "category_id"=> 5,
                "name"=> "KAWAI K-300",
                "image"=> "dan-piano-kawai-k300-mau-den-sang-trong-450x471.jpg",
                "price"=> 194700000,
                "price_sale"=> 182000000,
                "short_description"=> "Kawai K-300 kế thừa từ mẫu K3, từng đạt giải thưởng quốc tế, mang đến âm thanh ấn tượng và thiết kế mạnh mẽ, sang trọng."
            ],
            [
                "category_id"=> 5,
                "name"=> "KAWAI K-800",
                "image"=> "piano-kawai-k800-sang-trong-450x471.jpg",
                "price"=> 305600000,
                "price_sale"=> 285000000,
                "short_description"=> "Đàn piano Kawai K-800 nổi bật với thiết kế thanh lịch, âm thanh piano acoustic chất lượng cao, phù hợp cho những người yêu thích nghệ thuật âm nhạc."
            ],
            [
                "category_id"=> 6,
                "name"=> "KAWAI GL-10",
                "image"=> "GL10-Polished-Ebony-450x471.jpg",
                "price"=> 314800000,
                "price_sale"=> 295000000,
                "short_description"=> "Kawai GL-10 là một trong những cây baby grand piano nổi bật của Kawai, đem lại âm thanh tinh tế, thích hợp cho không gian nhỏ hoặc gia đình."
            ],
            [
                "category_id"=> 6,
                "name"=> "KAWAI GL-30",
                "image"=> "dan-piano-kawai-gl30-mau-den-450x471.jpg",
                "price"=> 425900000,
                "price_sale"=> 409000000,
                "short_description"=> "Kawai GL-30 với thiết kế thông minh và vật liệu chất lượng cao, đảm bảo độ bền bỉ và ổn định trong thời gian dài sử dụng."
            ],
            [
                "category_id"=> 6,
                "name"=> "KAWAI GL-50",
                "image"=> "dan-piano-kawai-gl50-sang-trong-450x471.jpg",
                "price"=> 492900000,
                "price_sale"=> 469000000,
                "short_description"=> "GL-50 là cây grand piano hoàn hảo cho các lớp học và phòng thu âm, mang đến trải nghiệm âm thanh và cảm xúc tuyệt vời cho người chơi."
            ],
            [
                "category_id"=> 5,
                "name"=> "ROLAND RP-30",
                "image"=> "bannerT9-RO30-2048-450x471.png",
                "price"=> 21760000,
                "price_sale"=> 16500000,
                "short_description"=> "RP-30 được thiết kế với phím đàn nhạy bén, chính xác, phù hợp cho người mới tập chơi. Ba bàn đạp tiêu chuẩn giúp nâng cao kỹ năng và cảm nhận âm nhạc."
            ],
            [
                "category_id"=> 6,
                "name"=> "KAWAI GX-3",
                "image"=> "kawai-gx-3-1-450x471.jpg",
                "price"=> 0,
                "price_sale"=> 577800000,
                "short_description"=> "Kawai GX-3 với dáng vẻ sang trọng và âm thanh đặc trưng của đại dương cầm, lý tưởng cho biểu diễn chuyên nghiệp và không gian cao cấp."
            ],
            [
                "category_id"=> 3,
                "name"=> "CORDOBA C1M 02685",
                "image"=> "cordoba-c1m-02685-450x471.jpg",
                "price"=> 4310000,
                "price_sale"=> 3520000,
                "short_description"=> "Córdoba C1M, cây đàn guitar nylon cổ điển hoàn hảo cho học sinh và người mới chơi, thiết kế tối giản nhưng chất lượng vượt trội."
            ],
            [
                "category_id"=> 4,
                "name"=> "CORDOBA C1M-CE",
                "image"=> "cordoba-C1M-CE-01-450x471.jpg",
                "price"=> 7790000,
                "price_sale"=> 6750000,
                "short_description"=> "Cordoba C1M-CE là cây guitar cổ điển dáng khuyết, âm thanh sâu lắng, chất liệu gỗ bền đẹp, lý tưởng cho những ai yêu thích phong cách cổ điển."
            ],
            [
                "category_id"=> 3,
                "name"=> "FENDER CD-60S",
                "image"=> "fender-cd-60s-450x471.jpg",
                "price"=> 6350000,
                "price_sale"=> 5650000,
                "short_description"=> "Fender CD-60S là cây guitar dreadnought mạnh mẽ với âm thanh to và ấm, hoàn hảo cho người chơi đệm hát và sử dụng pick."
            ],
            [
                "category_id"=> 3,
                "name"=> "TANGLEWOOD TWBB SDE",
                "image"=> "tanglewoodguitars-twbbsde-01-450x471.jpg",
                "price"=> 0,
                "price_sale"=> 6030000,
                "short_description"=> "Tanglewood TWBB SDE, guitar acoustic với mặt gỗ chọn lọc, kiểu dáng đẹp, tiếng đàn ấm áp phù hợp với mọi trình độ chơi."
            ],
            [
                "category_id"=> 3,
                "name"=> "TANGLEWOOD TWCR DCE CROSSROADS DREADNOUGHT ACOUSTIC",
                "image"=> "tanglewood-twcrdce-450x471.jpg",
                "price"=> 4140000,
                "price_sale"=> 3540000,
                "short_description"=> "TWCR DCE là cây guitar electro-acoustic chất lượng, thiết kế đẹp mắt và gỗ mahogany chất lượng cao."
            ],
            [
                "category_id"=> 3,
                "name"=> "KAPOK D-118AC",
                "image"=> "Kapok-D-118AC-1-400x400-450x471.jpg",
                "price"=> 2720000,
                "price_sale"=> 2100000,
                "short_description"=> "Kapok D-118AC với thiết kế nhỏ gọn, phù hợp cho người mới bắt đầu với mức giá phải chăng và chất lượng âm thanh ổn định."
            ],
            [
                "category_id"=> 3,
                "name"=> "KAPOK LD-14",
                "image"=> "kapok-ld-14-1-400x400-450x471.jpg",
                "price"=> 0,
                "price_sale"=> 2190000,
                "short_description"=> "Kapok LD-14 là cây guitar phổ thông, với lớp sơn bóng đẹp, cần đàn làm từ gỗ mahogany, mang lại vẻ ngoài và âm thanh nổi bật."
            ],
            [
                "category_id"=> 3,
                "name"=> "SUZUKI SDG-6NL",
                "image"=> "Suzuki-SDG-6NL-1-270x270-450x471.jpg",
                "price"=> 3426000,
                "price_sale"=> 2650000,
                "short_description"=> "Suzuki SDG-6NL, cây guitar dành cho sinh viên với giá hợp lý, kiểu dáng đẹp và âm thanh tuyệt vời, lý tưởng cho người mới bắt đầu."
            ],
            [
                "category_id"=> 4,
                "name"=> "GUITAR CLASSIC CORDOBA FUSION 5 KÈM BAG GUCLCOR-05407",
                "image"=> "guitar-classic-cordoba-fusion-5-kem-bag-guclcor-05407-450x471.jpg",
                "price"=> 0,
                "price_sale"=> 13100000,
                "short_description"=> "Cordoba Fusion 5, cây guitar classic thiết kế dáng cutaway hiện đại, dễ dàng tiếp cận các phím cao, đi kèm túi đựng chuyên nghiệp."
            ]
        ];

        foreach ($productsData as $data) {
            Product::create([
                'category_id' => $data['category_id'],
                'brand_id' => $brands->random()->id,
                'name' => $data['name'],
                'image' => $data['image'],
                'price' => $data['price'],
                'price_sale' => $data['price_sale'],
                'view' => $faker->numberBetween(0, 1000),
                'short_description' => $data['short_description'],
                'description' => $faker->paragraph,
                'publish' => $faker->boolean(80),
                'slug' => $faker->slug,
            ]);
        }
    }
}
