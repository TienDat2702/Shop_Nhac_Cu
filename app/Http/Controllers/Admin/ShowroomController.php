<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Showroom;
use App\Models\ShowroomProduct;
use App\Http\Requests\ShowroomRequest;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
class ShowroomController extends Controller
{
    public function index(Request $request)
{
    $countDeleted = Showroom::onlyTrashed()->count();

    // Kiểm tra xem có yêu cầu tìm kiếm hay không
    $config = $request->input('deleted') == 'daxoa' ? 'deleted' : 'index';

    if ($config === 'deleted') {
        // Tìm kiếm showroom đã bị xóa
        $getDeleted = Showroom::onlyTrashed()->Search($request->all());
        return view('admin.showroom.showroom_category.delete', compact('config', 'countDeleted', 'getDeleted'));
    } else {
        // Tìm kiếm showroom bình thường
        $dsshowroom = Showroom::Search($request->all());

        return view('admin.showroom.showroom_category.index', compact('dsshowroom', 'countDeleted', 'config'));
    }
}





    public function create()
{
    return view('admin.showroom.showroom_add.index'); // Trả về view tạo showroom
}

public function store(ShowroomRequest $request)
{
    // Lấy tên showroom từ input và chuẩn hóa về chữ thường
    $inputName = strtolower($request->input('name'));

    // Kiểm tra nếu tên showroom có chứa "kho" (không phân biệt chữ hoa/thường)
    if (preg_match('/kho/i', $inputName)) {
        // Kiểm tra xem trong database đã có showroom nào có tên chứa "kho" và publish = 4 chưa
        $existingShowroom = Showroom::whereRaw("LOWER(name) REGEXP 'kho'")->where('publish', 4)->first();

        // Nếu showroom đã tồn tại với tên chứa "kho" và trạng thái publish = 4, trả về thông báo lỗi
        if ($existingShowroom) {
            toastr()->error('Chỉ có thể có một showroom với tên chứa "kho" và trạng thái publish là 4.');
            return redirect()->back()->withInput();
        }

        // Gán publish = 4 nếu tên showroom chứa "kho"
        $publishStatus = 4;
    } else {
        // Gán publish = 2 cho các showroom khác
        $publishStatus = 2;
    }

    // Kiểm tra xem có showroom nào đã có publish = 4 (đối với các showroom khác)
    $existingPublishedShowroom = Showroom::where('publish', 4)->first();

    // Nếu showroom đã tồn tại với publish = 4 và showroom hiện tại không phải là "Kho", trả về thông báo lỗi
    if ($existingPublishedShowroom && $publishStatus == 4) {
        toastr()->error('Chỉ có thể có một showroom với trạng thái publish là 4.');
        return redirect()->back()->withInput();
    }

    // Tạo showroom mới
    $showroom = Showroom::create([
        'name' => $request->input('name'),
        'address' => $request->input('address'),
        'phone' => $request->input('phone'),
        'publish' => $publishStatus, // Đặt publish theo giá trị đã xác định
        'longitude' => $request->input('longitude'), // Lưu kinh độ
        'latitude' => $request->input('latitude'),   // Lưu vĩ độ
    ]);

    // Kiểm tra và xử lý hình ảnh
    if ($request->hasFile('image')) {
        $image = $request->file('image');
        $imageName = time() . '_' . $image->getClientOriginalName(); // Đặt tên file
        $image->move(public_path('uploads/showrooms'), $imageName); // Di chuyển file đến thư mục public/uploads/showrooms
        $showroom->image = 'uploads/showrooms/' . $imageName; // Lưu đường dẫn tương đối của ảnh
        $showroom->save(); // Lưu cập nhật showroom
    }

    // Kiểm tra thành công và hiển thị thông báo
    if ($showroom) {
        toastr()->success('Thêm mới thành công!');
    } else {
        toastr()->error('Thêm mới không thành công.');
    }

    // Redirect về trang danh sách showroom
    return redirect()->route('showroomcategory.index');
}

public function findNearestShowroom(Request $request)
{
    $lat = $request->query('lat');
    $lon = $request->query('lon');

    // Kiểm tra dữ liệu đầu vào
    if (!$lat || !$lon) {
        return response()->json(['error' => 'Missing latitude or longitude'], 400);
    }

    // Sử dụng công thức Haversine để tính khoảng cách giữa showroom và tọa độ người dùng
    $showrooms = Showroom::where('publish', 2)->selectRaw("
            id, name, latitude, longitude,
            ( 6371 * acos( cos( radians(?) ) *
              cos( radians( latitude ) ) *
              cos( radians( longitude ) - radians(?) ) +
              sin( radians(?) ) *
              sin( radians( latitude ) ) )
            ) AS distance", [$lat, $lon, $lat])
        // Bỏ điều kiện giới hạn khoảng cách nếu muốn lấy tất cả showroom
        // ->having('distance', '<', 50)
        ->orderBy('distance', 'asc')  // Sắp xếp showroom gần nhất
        ->get();

    // Kiểm tra nếu không có showroom nào
    if ($showrooms->isEmpty()) {
        return response()->json(['message' => 'No showrooms found near the specified location'], 404);
    }

    return response()->json([
        'showrooms' => $showrooms
    ]);
}






    public function edit($id)
{
    $showroom = Showroom::findOrFail($id);
    return view('admin.showroom.showroom_edit.index', compact('showroom'));
}

public function update(Request $request, $id)
{
    // Validate data from request
    $request->validate([
        'name' => 'required|string|max:125',
        'address' => 'nullable|string|max:225',
        'phone' => 'nullable|string|max:30',
        'image' => 'nullable|image|max:2048',
        'longitude' => 'nullable|numeric', // Add validation for longitude
        'latitude' => 'nullable|numeric',  // Add validation for latitude
    ]);

    // Find showroom by id
    $showroom = Showroom::findOrFail($id);

    // If showroom's publish status is 4, allow editing freely
    if ($showroom->publish == 4) {
        $inputName = strtolower($request->input('name'));

        // Check if the name doesn't contain "Kho", "Khoo", "Khooo", "fpt"
        if (!preg_match('/kho|fpt/i', $inputName)) {
            // If name does not contain those terms, set publish status to 2
            $showroom->publish = 2;
        }
    } else {
        // Prepare name to check for the "Kho" condition
        $inputName = strtolower($request->input('name'));

        // Check if showroom name contains "kho"
        if (preg_match('/kho/i', $inputName)) {
            $existingShowroom = Showroom::whereRaw("LOWER(name) REGEXP 'kho'")->where('publish', 4)->first();

            // If there's already a showroom with "kho" and publish = 4, return error
            if ($existingShowroom) {
                toastr()->error('Chỉ có thể có một showroom với tên chứa "kho" và trạng thái publish là 4.');
                return redirect()->back()->withInput();
            }

            // Set publish status to 4 if the name contains "kho"
            $showroom->publish = 4;
        } else {
            $showroom->publish = 2;
        }

        // Ensure there's only one showroom with publish = 4
        $existingPublishedShowroom = Showroom::where('publish', 4)->first();

        if ($existingPublishedShowroom && $showroom->publish == 4) {
            toastr()->error('Chỉ có thể có một showroom với trạng thái publish là 4.');
            return redirect()->back()->withInput();
        }
    }

    // Check if the name hasn't changed, then update only other fields
    if ($showroom->name === $request->name) {
        $showroom->address = $request->address;
        $showroom->phone = $request->phone;
        $showroom->longitude = $request->longitude;
        $showroom->latitude = $request->latitude;

        // Handle image upload if a new image is provided
        if ($request->hasFile('image')) {
            // Delete old image if it exists
            if ($showroom->image) {
                $oldImagePath = public_path($showroom->image);
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath); // Delete old image
                }
            }

            // Upload new image
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName(); // Set file name
            $image->move(public_path('uploads/showrooms'), $imageName); // Move file to uploads directory
            $showroom->image = 'uploads/showrooms/' . $imageName; // Save relative image path
        }

        // Save showroom
        $showroom->save();
        toastr()->success('Hoàn tất thay đổi!');
        return redirect()->route('showroomcategory.index');
    }

    // Check for duplicate showroom name
    $existingShowroom = Showroom::where('name', $request->name)
                                ->where('id', '!=', $id) // Ensure it doesn't compare with the current showroom
                                ->first();

    if ($existingShowroom) {
        toastr()->error('Tên showroom này đã tồn tại trong cơ sở dữ liệu!');
        return redirect()->back(); // Return to previous page
    }

    // Update showroom name and other details
    $showroom->name = $request->name;
    $showroom->address = $request->address;
    $showroom->phone = $request->phone;
    $showroom->longitude = $request->longitude;
    $showroom->latitude = $request->latitude;

    // Handle image upload if a new image is provided
    if ($request->hasFile('image')) {
        // Delete old image if it exists
        if ($showroom->image) {
            $oldImagePath = public_path($showroom->image);
            if (file_exists($oldImagePath)) {
                unlink($oldImagePath); // Delete old image
            }
        }

        // Upload new image
        $image = $request->file('image');
        $imageName = time() . '_' . $image->getClientOriginalName(); // Set file name
        $image->move(public_path('uploads/showrooms'), $imageName); // Move file to uploads directory
        $showroom->image = 'uploads/showrooms/' . $imageName; // Save relative image path
    }

    // Save showroom
    $showroom->save();
    toastr()->success('Hoàn tất thay đổi!');
    // Redirect to showroom category index with success message
    return redirect()->route('showroomcategory.index');
}




public function forceDelete(string $id)
{
    $showroom = Showroom::onlyTrashed()->find($id);

    if (!$showroom) {
        toastr()->error('Dữ liệu không tồn tại!');
        return redirect()->back();
    } else {
        // Nếu showroom có hình ảnh, xóa hình ảnh
        if ($showroom->image) {
            $image_path = public_path($showroom->image); // Sử dụng public_path để lấy đường dẫn đầy đủ
            if (file_exists($image_path)) {
                unlink($image_path);
            }
        }
        $showroom->forceDelete(); // Xóa showroom vĩnh viễn
        toastr()->success('Xóa thành công!');
        return redirect()->back();
    }
}
public function destroy(string $id)
{
    // Tìm showroom theo id
    $showroom = Showroom::GetWithParent()->find($id);

    if (!$showroom) {
        return redirect()->back()->withErrors(['Showroom không tồn tại!']);
    }

    // Ngăn không cho xóa nếu trạng thái publish là 4
    if ($showroom->publish == 4) {
        return redirect()->back()->withErrors(['Không thể xóa showroom này vì nó đang ở trạng thái không cho phép xóa!']);
    }

    // Kiểm tra xem showroom có sản phẩm liên kết thông qua bảng showroom_products không
    $hasProducts = ShowroomProduct::where('showroom_id', $showroom->id)->exists();

    if ($hasProducts) {
        return redirect()->back()->withErrors(['Không thể xóa showroom vì còn sản phẩm liên kết!']);
    }

    // Đặt trạng thái publish về 1 (không hoạt động)
    $showroom->publish = 1; // Hoặc bạn có thể đặt thành 0 tùy thuộc vào quy tắc của bạn
    $showroom->save();

    // Đánh dấu showroom là đã xóa
    $showroom->delete(); // Gọi phương thức delete() để thực hiện soft delete

    toastr()->success('Xóa showroom thành công!');
    return redirect()->back();
}
public function restore(string $id)
{
    $showroom = Showroom::onlyTrashed()->find($id);

    if (!$showroom) {
        return redirect()->back()->withErrors(['showroom không tồn tại!']);
    } else {
        $showroom->publish = 2; // Đặt trạng thái publish về 2 (hoạt động)
        $showroom->save(); // Lưu thay đổi
        $showroom->restore(); // Khôi phục showroom
        toastr()->success('Khôi phục thành công!');
        return redirect()->route('showroomcategory.index');
    }
}




}
