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
        $countDeleted = Product::onlyTrashed()->count();
        if ($request->input('deleted') == 'daxoa') {
            $config = 'deleted';
            $getDeleted = Product::onlyTrashed()->search($request->all()); 
            return view('admin.products.product.index', compact('config', 'countDeleted', 'getDeleted'));
        } else {
            $config = 'index';
            $products = Product::search($request->all()); 
            return view('admin.products.product.index', compact('products', 'countDeleted', 'config'));
        }
    }

    public function create()
    {
        $categories = ProductCategory::all();
        $brands = Brand::all();
        return view('admin.products.product.create', compact('categories', 'brands'));
    }

    public function store(ProductCreateRequest $request)
    {
        $product = Product::create([
            'category_id' => $request->input('category_id'),
            'brand_id' => $request->input('brand_id'),
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'price' => $request->input('price'),
            'price_sale' => $request->input('price_sale'),
            'summary' => $request->input('summary'),
        ]);

        $uploadPath = public_path('uploads/products/product');
        $this->uploadImageService->uploadImage($request, $product, $uploadPath);

        if ($product) {
            toastr()->success('Thêm mới sản phẩm thành công!');
        } else {
            toastr()->error('Thêm mới sản phẩm không thành công.');
        }
        return redirect()->route('product.index');
    }

    public function edit(string $id)
    {
        $product = Product::find($id);
        if (!$product) {
            return redirect()->back()->withErrors(['Sản phẩm không tồn tại!']);
        }

        $categories = ProductCategory::all();
        $brands = Brand::all();
        return view('admin.products.product.update', compact('product', 'categories', 'brands'));
    }

    public function update(ProductUpdateRequest $request, string $id)
    {
        $product = Product::find($id);
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

        $product->update($data);

        if ($product->wasChanged()) {
            toastr()->success('Cập nhật sản phẩm thành công!');
        } else {
            toastr()->success('Không có gì thay đổi!');
        }
        return redirect()->route('product.index');
    }

    public function destroy(string $id)
    {
        $product = Product::find($id);
        if (!$product) {
            return redirect()->back()->withErrors(['Sản phẩm không tồn tại!']);
        }

        $product->publish = 0;
        $product->save();

        $product->delete();
        toastr()->success('Xóa sản phẩm thành công!');
        return redirect()->back();
    }

    public function restore(string $id)
    {
        $product = Product::onlyTrashed()->find($id);
        if (!$product) {
            return redirect()->back()->withErrors(['Sản phẩm không tồn tại!']);
        }
        
        $product->restore();
        $product->publish = 2;
        $product->save();

        toastr()->success('Khôi phục sản phẩm thành công!');
        return redirect()->back();
    }

    public function forceDelete(string $id)
    {
        $product = Product::onlyTrashed()->find($id);
        if (!$product) {
            toastr()->error('Dữ liệu không tồn tại!');
            return redirect()->back();
        }
        
        if ($product->image) {
            $image_path = 'uploads/products/product' . $product->image;
            if (file_exists($image_path)) {
                unlink($image_path);
            }
        }
        $product->forceDelete();
        toastr()->success('Xóa sản phẩm thành công!');
        return redirect()->back();
    }
}
