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
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Kiểm tra định dạng hình ảnh
    ]);

    // Tạo một showroom mới
    $banner = new Banner();

    // Xử lý upload hình ảnh
    if ($request->hasFile('image')) {
        // Lấy thông tin file hình ảnh
        $image = $request->file('image');
        $imageName = time() . '_' . $image->getClientOriginalName(); // Đặt tên file
        $image->move(public_path('uploads/banner'), $imageName); // Di chuyển file đến thư mục public/uploads/banner
        $banner->image = 'uploads/banner/' . $imageName; // Lưu đường dẫn vào cơ sở dữ liệu
    }

    $banner->publish = 2; // Mặc định là 1 (hoạt động)
    $banner->save(); // Lưu banner vào cơ sở dữ liệu

    return redirect()->route('banner.index')->with('success', 'banner added successfully.');
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
    // Validate dữ liệu từ request
    $request->validate([
        'image' => 'nullable|image|max:2048', // Kiểm tra ảnh nếu có
    ]);

    // Tìm showroom theo id
    $banner = banner::findOrFail($id);

    // Nếu có upload hình ảnh mới
    if ($request->hasFile('image')) {
        // Kiểm tra xem showroom có hình ảnh cũ hay không và xóa nếu cần
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

    // Lưu banner
    $banner->save();

    // Redirect về trang danh sách showroom và hiển thị thông báo thành công
    return redirect()->route('banner.index')->with('success', 'Showroom cập nhật thành công.');
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
