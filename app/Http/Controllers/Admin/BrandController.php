<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\BrandCreateRequest;
use App\Http\Requests\BrandUpdateRequest;
use App\Services\UploadImageService;
use App\Models\Brand;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    protected $uploadImageService;

    public function __construct(UploadImageService $uploadImageService)
    {
        $this->uploadImageService = $uploadImageService;
    }

    //---------------------------------- Trang index-------------------------------------------

    public function index(Request $request)
    {
        $countDeleted = Brand::onlyTrashed()->count(); // Đếm số phần tử đã bị xóa
        // Kiểm tra nếu là trang đã xóa
        if ($request->input('deleted') == 'daxoa') {
            $config = 'deleted';
            $getDeleted = Brand::onlyTrashed()->search($request->all()); // Sử dụng scopeSearch từ model
            return view('admin.brands.index', compact('config', 'countDeleted', 'getDeleted'));
        } else {
            $config = 'index';
            $brands = Brand::search($request->all()); // Sử dụng scopeSearch từ model
            return view('admin.brands.index', compact('brands', 'countDeleted', 'config'));
        }
    }

    public function create()
    {
        return view('admin.brands.create');
    }

    public function store(BrandCreateRequest $request)
    {
        $brand = Brand::create([
            'name' => $request->input('name'),
            'description' => $request->input('description'),
        ]);

        $uploadPath = public_path('uploads/brands');
        $this->uploadImageService->uploadImage($request, $brand, $uploadPath);

        if ($brand) {
            toastr()->success('Thêm mới thành công!');
        } else {
            toastr()->error('Thêm mới không thành công.');
        }
        return redirect()->route('brand.index');
    }

    public function edit(string $id)
    {
        $brand = Brand::find($id);
        if (!$brand) {
            return redirect()->back()->withErrors(['Thương hiệu không tồn tại!']);
        }
        return view('admin.brands.update', compact('brand'));
    }

    public function update(BrandUpdateRequest $request, string $id)
    {
        $brand = Brand::find($id);
        if (!$brand) {
            return redirect()->back()->withErrors(['Thương hiệu không tồn tại!']);
        }

        $data = [
            'name' => $request->input('name'),
            'description' => $request->input('description'),
        ];

        if ($request->hasFile('image')) {
            if ($brand->image) {
                $image_path = 'uploads/brands/' . $brand->image;
                if (file_exists($image_path)) {
                    unlink($image_path);
                }
            }
            $uploadPath = public_path('uploads/brands');
            $this->uploadImageService->uploadImage($request, $brand, $uploadPath);
        }

        $brand->update($data);

        if ($brand->wasChanged()) {
            toastr()->success('Cập nhật thành công!');
        } else {
            toastr()->success('Không có gì thay đổi!');
        }
        return redirect()->route('brand.index');
    }

    public function destroy(string $id)
    {
        $brand = Brand::find($id);
        if (!$brand) {
            return redirect()->back()->withErrors(['Thương hiệu không tồn tại!']);
        }
    
        // Cập nhật trạng thái trước khi xóa
        $brand->update(['publish' => 0]);
    
        // Xóa thương hiệu
        $brand->delete();
        toastr()->success('Xóa thành công!');
        return redirect()->back();
    }
  

    public function restore(string $id)
    {
        $brand = Brand::onlyTrashed()->find($id);
        if (!$brand) {
            return redirect()->back()->withErrors(['Thương hiệu không tồn tại!']);
        }
        $brand->restore();
    
        // Cập nhật trạng thái sau khi khôi phục
        $brand->update(['publish' => 2]);
    
        toastr()->success('Khôi phục thành công!');
        return redirect()->back();
    }

    public function forceDelete(string $id)
    {
        $brand = Brand::onlyTrashed()->find($id);
        if (!$brand) {
            toastr()->error('Dữ liệu không tồn tại!');
            return redirect()->back();
        }
        
        if ($brand->image) {
            $image_path = 'uploads/brands/' . $brand->image;
            if (file_exists($image_path)) {
                unlink($image_path);
            }
        }
        $brand->forceDelete();
        toastr()->success('Xóa thành công!');
        return redirect()->back();
    }
}
