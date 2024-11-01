<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;
class BannerController extends Controller
{
    public function index(Request $request){
        $countDeleted = Banner::onlyTrashed()->count();
        if ($request->input('deleted') == 'daxoa') {
            $config = 'deleted';
            $getDeleted = Banner::onlyTrashed()->get();
            return view('admin.banner.delete', compact('config', 'countDeleted', 'getDeleted'));
        } else {
            $config = 'index';
            $dsbanner = Banner::GetWithParent()->get();
        return view('admin.banner.index', compact('dsbanner',  'countDeleted', 'config'));
    }
    }
    public function create()
{
    return view('admin.banner.banner_add.index'); // Trả về view tạo showroom
}

public function store(Request $request)
{
    // Xác thực dữ liệu đầu vào
    $request->validate([
        'images.*' => 'image|max:2048', // Kiểm tra tất cả các hình ảnh
        'order.*' => 'required|integer|min:0', // Kiểm tra order
        'position.*' => 'required|integer|min:0', // Kiểm tra position
        'title.*' => 'required|string|max:255', // Xác thực trường title
        'strong_title.*' => 'required|string|max:255', // Xác thực trường strong_title
    ]);

    $uploadedImages = []; // Mảng lưu trữ đường dẫn hình ảnh

    // Lặp qua tất cả hình ảnh và lưu trữ
    foreach ($request->file('images') as $index => $image) {
        // Tạo một banner mới
        $banner = new Banner();

        // Xử lý upload hình ảnh
        if ($image) {
            $imageName = time() . '_' . $image->getClientOriginalName(); // Đặt tên file
            $image->move(public_path('uploads/banner'), $imageName); // Di chuyển file đến thư mục public/uploads/banner
            $banner->image = 'uploads/banner/' . $imageName; // Lưu đường dẫn vào cơ sở dữ liệu
            $uploadedImages[] = $banner->image; // Thêm hình ảnh vào mảng
        }

        // Lưu giá trị từ request
        $banner->position = $request->input('position')[$index]; // Lưu giá trị position từ request
        $banner->order = $request->input('order')[$index]; // Lưu giá trị order từ request
        $banner->title = $request->input('title')[$index]; // Lưu giá trị title từ request
        $banner->strong_title = $request->input('strong_title')[$index]; // Lưu giá trị strong_title từ request
        $banner->publish = 2; // Mặc định là 2 (không hoạt động)
        $banner->save(); // Lưu banner vào cơ sở dữ liệu
    }

    toastr()->success('Thêm Banner Thành Công!');
    
    // Chuyển hướng về trang chỉ định với đường dẫn hình ảnh đã tải lên
    return redirect()->route('banner.index')->with('uploadedImages', $uploadedImages);
}




    public function togglePublish($id, Request $request)
{
    $banner = Banner::findOrFail($id); // Tìm banner theo ID

    // Lấy giá trị publish từ checkbox
    $banner->publish = $request->has('publish') ? 2 : 1; // Nếu checked thì publish = 2, ngược lại = 1
    $banner->save(); // Lưu thay đổi vào cơ sở dữ liệu

    return redirect()->route('banner.index')->with('success', 'Trạng thái publish đã được cập nhật.');
}
public function edit($id)
{
    $banner = Banner::findOrFail($id);

    return view('admin.banner.banner_edit.index', compact('banner'));
}

public function update(Request $request, $id)
{
    try {
        // Validate dữ liệu từ request
        $request->validate([
            'image' => 'nullable|image|max:2048',
            'position' => 'required|integer|min:0',
            'order' => 'required|integer|min:0', // Kiểm tra position
        ]);

        // Tìm banner theo id
        $banner = Banner::findOrFail($id);

        // Nếu có upload hình ảnh mới
        if ($request->hasFile('image')) {
            // Kiểm tra xem banner có hình ảnh cũ hay không và xóa nếu cần
            if ($banner->image) {
                $oldImagePath = public_path($banner->image);
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath); // Xóa hình ảnh cũ
                }
            }

            // Upload hình ảnh mới và lưu vào đúng thư mục
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName(); // Đặt tên file
            $image->move(public_path('uploads/banner'), $imageName); // Di chuyển file đến thư mục public/uploads/banner
            $banner->image = 'uploads/banner/' . $imageName; // Lưu đường dẫn tương đối của ảnh
        }

        // Cập nhật giá trị position
        $banner->position = $request->input('position'); // Lưu giá trị position từ request
        $banner->order = $request->input('order'); 
        // Lưu banner
        $banner->save();

        toastr()->success('Cập nhật Banner Thành Công');
        
        // Redirect về trang danh sách banner
        return redirect()->route('banner.index');
    } catch (\Exception $e) {
        // Nếu có lỗi xảy ra
        toastr()->error('Đã xảy ra lỗi: ' . $e->getMessage());
        
        // Redirect về trang danh sách banner
        return redirect()->route('banner.index')->withInput();
    }
}

public function restore(string $id)
{
    $banner = Banner::onlyTrashed()->find($id);

    if (!$banner) {
        return redirect()->back()->withErrors(['Banner không tồn tại!']);
    } else {
        $banner->publish = 2; // Đặt trạng thái publish về 2 (hoạt động)
        $banner->save(); // Lưu thay đổi
        $banner->restore(); // Khôi phục showroom
        toastr()->success('Khôi phục thành công!');
        return redirect()->back();
    }
}


public function forceDelete(string $id)
{
    $banner = Banner::onlyTrashed()->find($id);

    if (!$banner) {
        toastr()->error('Dữ liệu không tồn tại!');
        return redirect()->back();
    } else {
        // Nếu banner có hình ảnh, xóa hình ảnh
        if ($banner->image) {
            // Chuyển đổi tất cả dấu gạch chéo ngược thành gạch chéo xuôi
            $image_path = public_path(str_replace('\\', '/', $banner->image));
            
            if (file_exists($image_path)) {
                unlink($image_path);
            } else {
                dd("File not found: " . $image_path); // Kiểm tra đường dẫn
            }
        }        
        $banner->forceDelete(); // Xóa showroom vĩnh viễn
        toastr()->success('Xóa thành công!');
        return redirect()->back();
    }
}
public function destroy(string $id)
{
    // Tìm showroom theo id
    $banner = Banner::GetWithParent()->find($id);

    if (!$banner) {
        return redirect()->back()->withErrors(['banner không tồn tại!']);
    }

    // Đặt trạng thái publish về 1 (không hoạt động)
    $banner->publish = 1; // Hoặc bạn có thể đặt thành 0 tùy thuộc vào quy tắc của bạn
    $banner->save();

    // Đánh dấu showroom là đã xóa
    $banner->delete(); // Gọi phương thức delete() để thực hiện soft delete

    toastr()->success('Xóa showroom thành công!');
    return redirect()->back();
}
}
