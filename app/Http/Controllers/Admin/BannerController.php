<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Http\Requests\BannerRequest;
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

public function store(BannerRequest $request)
{
    // Kiểm tra xem có banner nào có cùng order và position không
    $existingBanner = Banner::where('order', $request->order)
                            ->where('position', $request->position)
                            ->first();

    if ($existingBanner) {
        toastr()->error("Order {$request->order} và Position {$request->position} đã tồn tại. Vui lòng chọn giá trị khác.");
        return redirect()->back()->withInput();
    }

    // Tạo một banner mới
    $banner = new Banner();

    // Xử lý upload hình ảnh
    if ($request->hasFile('image')) {
        $image = $request->file('image');
        $imageName = time() . '_' . $image->getClientOriginalName(); // Đặt tên file
        $image->move(public_path('uploads/banner'), $imageName); // Di chuyển file đến thư mục public/uploads/banner
        $banner->image = 'uploads/banner/' . $imageName; // Lưu đường dẫn vào cơ sở dữ liệu
    }

    // Lưu giá trị từ request
    $banner->order = $request->order;
    $banner->position = $request->position;
    $banner->title = $request->title;
    $banner->strong_title = $request->strong_title;
    $banner->description = $request->description ?? null;
    $banner->publish = 2; // Mặc định không hoạt động
    $banner->save();

    toastr()->success('Thêm Banner Thành Công!');

    return redirect()->route('banner.index');
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

public function update(BannerRequest $request, $id)
{
    try {
        // Tìm banner theo id
        $banner = Banner::findOrFail($id);

        // Kiểm tra xem có banner nào khác có cùng giá trị order và position
        $existingBanner = Banner::where('order', $request->input('order'))
                                ->where('position', $request->input('position'))
                                ->where('id', '!=', $id) // Loại trừ banner hiện tại
                                ->first();

        if ($existingBanner) {
            toastr()->error('Đã tồn tại banner khác với order và position này. Vui lòng chọn giá trị khác.');
            return redirect()->back()->withInput();
        }

        // Cập nhật title và strong_title từ request
        $banner->title = $request->input('title');
        $banner->strong_title = $request->input('strong_title');

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

        // Cập nhật giá trị position và order
        $banner->position = $request->input('position');
        $banner->order = $request->input('order');

        // Lưu banner
        $banner->save();

        toastr()->success('Cập nhật Banner Thành Công');

        // Redirect về trang danh sách banner
        return redirect()->route('banner.index');
    } catch (\Exception $e) {
        // Nếu có lỗi xảy ra
        toastr()->error('Đã xảy ra lỗi: ' . $e->getMessage());

        // Redirect về trang danh sách banner với dữ liệu đã nhập
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
