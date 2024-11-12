<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Brand;
use App\Models\Banner;
use App\Models\ThumbnailProduct;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        // Lấy danh sách banner, danh mục sản phẩm và thương hiệu
        $banners = Banner::where('position', 2)->where('publish', 2)->get();
        $productCategories = $this->getRecursive();
        $brands = Brand::GetBrandPublish()->get();

        // Định nghĩa các phân khúc giá
        $priceSegments = [
            '0-20000000' => 'Dưới 20 triệu',
            '20000000-50000000' => '20 triệu - 50 triệu',
            '50000000-100000000' => '50 triệu - 100 triệu',
            '100000000-200000000' => '100 triệu - 200 triệu',
            '200000000-500000000' => '200 triệu - 500 triệu',
            '500000000-999999999' => 'Trên 500 triệu'
        ];

        // Khởi tạo truy vấn sản phẩm
        $productsQuery = Product::GetProductPublish();

        // Lọc sản phẩm theo khoảng giá nếu có
        if ($request->has('price_segment') && !empty($request->price_segment)) {
            $priceSegmentsChecked = (array) $request->price_segment; // Đảm bảo price_segment luôn là mảng
            $productsQuery->where(function ($query) use ($priceSegmentsChecked) {
                foreach ($priceSegmentsChecked as $segment) {
                    $range = explode('-', $segment);
                    if (count($range) === 2) {
                        $minPrice = (float) $range[0];
                        $maxPrice = (float) $range[1];
                        $query->orWhere(function ($subQuery) use ($minPrice, $maxPrice) {
                            $subQuery->whereBetween('price_sale', [$minPrice, $maxPrice])
                                ->orWhere(function ($innerQuery) use ($minPrice, $maxPrice) {
                                    $innerQuery->whereNull('price_sale')
                                        ->whereBetween('price', [$minPrice, $maxPrice]);
                                });
                        });
                    }
                }
            });
        }

        // Lọc sản phẩm theo thương hiệu nếu có chọn
        if ($request->has('brand_ids') && is_array($request->brand_ids) && count($request->brand_ids) > 0) {
            $productsQuery->whereIn('brand_id', $request->brand_ids);
        }

        // Lọc theo danh mục nếu có slug trong URL
        if ($request->has('category_slug') && !empty($request->category_slug)) {
            $category = ProductCategory::GetAllByPublish()->where('slug', $request->category_slug)->first();
            if ($category) {
                $productsQuery->where('category_id', $category->id);
            }
        }
        // Phân trang sản phẩm
        $products = $productsQuery->paginate(9);

        if (!$products->isEmpty()) {
            return view('user.shop', compact('products', 'productCategories', 'brands', 'banners', 'priceSegments'))->with('currentCategory', null);
        } else {
            toastr()->warning('Không có sản phẩm!');
            return view('user.shop', compact('products', 'productCategories', 'brands', 'banners', 'priceSegments'))->with('currentCategory', null);
        }
    }





    public function getRecursive()
    {
        $productCategories = ProductCategory::GetAllByPublish()->get();
        $listCategories = [];
        ProductCategory::recursive($productCategories, 0, 1, $listCategories);
        return $listCategories;
    }

    public function category($slug, Request $request)
    {
        // Tìm danh mục dựa trên slug
        $category = ProductCategory::GetAllByPublish()->where('slug', $slug)->first();
        if (!$category) {
            return redirect()->route('shop.index')->with('error', 'Danh mục không tồn tại.');
        }

        // Lấy danh sách danh mục sản phẩm và thương hiệu
        $productCategories = $this->getRecursive();
        $brands = Brand::GetBrandPublish()->get();

        // Định nghĩa các phân khúc giá
        $priceSegments = [
            '0-20000000' => 'Dưới 20 triệu',
            '20000000-50000000' => '20 triệu - 50 triệu',
            '50000000-100000000' => '50 triệu - 100 triệu',
            '100000000-200000000' => '100 triệu - 200 triệu',
            '200000000-500000000' => '200 triệu - 500 triệu',
            '500000000-999999999' => 'Trên 500 triệu'
        ];

        // Khởi tạo truy vấn sản phẩm cho danh mục hiện tại
        $productsQuery = Product::GetProductPublish()->where('category_id', $category->id);

        // Lọc sản phẩm theo khoảng giá nếu có
        if ($request->has('price_segment') && !empty($request->price_segment)) {
            $priceSegmentsChecked = $request->price_segment;
            if (is_array($priceSegmentsChecked)) {
                $productsQuery->where(function ($query) use ($priceSegmentsChecked) {
                    foreach ($priceSegmentsChecked as $segment) {
                        $range = explode('-', $segment);
                        if (count($range) === 2) {
                            $minPrice = (float) $range[0];
                            $maxPrice = (float) $range[1];
                            $query->orWhere(function ($subQuery) use ($minPrice, $maxPrice) {
                                $subQuery->whereBetween('price_sale', [$minPrice, $maxPrice])
                                    ->orWhere(function ($innerQuery) use ($minPrice, $maxPrice) {
                                        $innerQuery->whereNull('price_sale')
                                            ->whereBetween('price', [$minPrice, $maxPrice]);
                                    });
                            });
                        }
                    }
                });
            }
        }

        // Lọc sản phẩm theo thương hiệu nếu có
        if ($request->filled('brand_ids')) {
            $productsQuery->whereIn('brand_id', $request->brand_ids);
        }

        // Thực hiện phân trang
        $products = $productsQuery->paginate(9);

        if (!$products->isEmpty()) {
            return view('user.shop', compact('products', 'productCategories', 'brands', 'priceSegments'))->with('currentCategory', $category);
        } else {
            toastr()->warning('Không có sản phẩm!');
            return view('user.shop', compact('products', 'productCategories', 'brands', 'priceSegments'))->with('currentCategory', $category);
        }
    }

    public function product_details($slug)
    {
        $product = Product::where('slug', $slug)->first();

        $product->view += 1;
        $product->save();
        $brand = Brand::find($product->brand_id);
        $product_images = ThumbnailProduct::where('product_id', $product->id)->get();
        $product_related = Product::where('category_id', $product->category_id)->where('slug', '!=', $slug)->get();
        return view('user.product_detail', compact('product', 'brand', 'product_images', 'product_related'));
    }
}
