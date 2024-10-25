<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductCreateRequest;
use App\Http\Requests\ProductUpdateRequest;
use App\Models\Brand;
use App\Services\UploadImageService;
use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Http\Request;

class AdminProductController extends Controller
{
    protected $uploadImageService;

    public function __construct(UploadImageService $uploadImageService)
    {
        $this->uploadImageService = $uploadImageService;
    }

    public function index(Request $request)
    {
        $brand = Brand::GetBrandAll()->get();
        $productCategories = $this->getRecursive();
        $countDeleted = Product::onlyTrashed()->count();
        if ($request->input('deleted') == 'daxoa') {
            $config = 'deleted';
            $getDeleted = Product::onlyTrashed()->search($request->all()); 
            return view('admin.products.product.index', compact('config', 'countDeleted', 'getDeleted', 'productCategories', 'brand'));
        } else {
            $config = 'index';
            $products = Product::search($request->all()); 
            return view('admin.products.product.index', compact('products', 'countDeleted', 'config', 'productCategories', 'brand'));
        }
    }

    public function getRecursive()
    {
        $productCategories = ProductCategory::GetAllByPublish()->get();
        $listCategories = [];
        ProductCategory::recursive($productCategories, 0, 1, $listCategories);
        return $listCategories;
    }

    public function create()
    {
        $categories = ProductCategory::all();
        $brands = Brand::all();
        return view('admin.products.product.create', compact('categories', 'brands'));
    }

    public function store(ProductCreateRequest $request)
    {

        $slug = Product::GenerateUniqueSlug($request->input('name'));
        $product = Product::create([
            'category_id' => $request->input('category_id'),
            'brand_id' => $request->input('brand_id'),
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'price' => $request->input('price'),
            'price_sale' => $request->input('price_sale'),
            'slug' => $slug,
        ]);

        $uploadPath = public_path('uploads/products/product');
        $this->uploadImageService->uploadImage($request, $product, $uploadPath);

        // xử lý album
        $path = 'uploads/products/thumbnails';
        $relation = 'thumbnails';
        $this->uploadImageService->uploadAlbum($request, $product, $path, $relation);

        if ($product) {
            toastr()->success('Thêm mới thành công!');
        } else {
            toastr()->error('Thêm mới không thành công.');
        }
        return redirect()->route('product.index');
    }

    public function edit(string $id)
    {
        $product = Product::findOrFail($id);
        if (!$product) {
            return redirect()->back()->withErrors(['Sản phẩm không tồn tại!']);
        }

        $categories = ProductCategory::all();
        $brands = Brand::all();
        $thumbnails = $product->thumbnails->pluck('path');
        return view('admin.products.product.update', compact('product', 'categories', 'brands', 'thumbnails'));
    }

    public function update(ProductUpdateRequest $request, string $id)
    {
        $product = Product::findOrFail($id);
        if (!$product) {
            return redirect()->back()->withErrors(['Sản phẩm không tồn tại!']);
        }

        $data = [
            'category_id' => $request->input('category_id'),
            'brand_id' => $request->input('brand_id'),
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'price' => $request->input('price'),
            'price_sale' => $request->input('price_sale'),
            'summary' => $request->input('summary'),
        ];

        if ($request->hasFile('image')) {
            if ($product->image) {
                $image_path = 'uploads/products/product' . $product->image;
                if (file_exists($image_path)) {
                    unlink($image_path);
                }
            }
            $uploadPath = public_path('uploads/products/product');
            $this->uploadImageService->uploadImage($request, $product, $uploadPath);
        }

        // xử lý album
        $path = 'uploads/products/thumbnails';
        $relation = 'thumbnails';
        $this->uploadImageService->uploadAlbum($request, $product, $path, $relation);

        $product->update($data);

        if ($product->wasChanged()) {
            toastr()->success('Cập nhật thành công!');
        } else {
            toastr()->success('Không có gì thay đổi!');
        }
        return redirect()->route('product.index');
    }

    public function destroy(string $id)
    {
        $product = Product::findOrFail($id);
        if (!$product) {
            return redirect()->back()->withErrors(['Sản phẩm không tồn tại!']);
        }
        if($product && $product->image){
            $image_path = 'uploads/products/thumbnails/' . $product->image;
            if (file_exists($image_path)) { // tìm vào đường dẫn ảnh
                unlink($image_path); // xóa đường dẩn chứ ảnh cũ
            }
        }
        $product->publish = 1;
        $product->save();

        $product->delete();
        toastr()->success('Xóa thành công!');
        return redirect()->back();
    }

    public function restore(string $id)
    {
        $product = Product::onlyTrashed()->findOrFail($id);
        if (!$product) {
            return redirect()->back()->withErrors(['Sản phẩm không tồn tại!']);
        }
        
        $product->restore();
        $product->publish = 2;
        $product->save();

        toastr()->success('Khôi phục thành công!');
        return redirect()->back();
    }

    public function forceDelete(string $id)
    {
        $product = Product::onlyTrashed()->findOrFail($id);
        if (!$product) {
            toastr()->error('Dữ liệu không tồn tại!');
            return redirect()->back();
        }
        // Lấy tất cả các hình ảnh album liên quan đến bài viết
        $thumbnails = $product->thumbnails->pluck('path')->toArray();
         // Xóa các bản ghi album khỏi cơ sở dữ liệu
         foreach ($thumbnails as $thumbnail) {
            // Xóa ảnh khỏi cơ sở dữ liệu
            $product->thumbnails()->where('path', $thumbnail)->delete();

            // Xóa file khỏi hệ thống
            $filePath = str_replace(url('/'), public_path(), $thumbnail);
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }
        if ($product->image) {
            $image_path = 'uploads/products/product' . $product->image;
            if (file_exists($image_path)) {
                unlink($image_path);
            }
        }
        $product->forceDelete();
        toastr()->success('Xóa thành công!');
        return redirect()->back();
    }
}
