<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Showroom;
use App\Models\ShowroomProduct;
use App\Models\Product;
use Illuminate\Http\Request;
class ShowroomController extends Controller
{
    public function index(Request $request){
        $countDeleted = Showroom::onlyTrashed()->count();
        if ($request->input('deleted') == 'daxoa') {
            $config = 'deleted';
            $getDeleted = Showroom::onlyTrashed()->Search($request->all());
            return view('admin.showroom.showroom_category.delete', compact('config', 'countDeleted', 'getDeleted'));
        } else {
            $config = 'index';
            $products = Product::all();
            $dsshowroom = Showroom::GetWithParent()->Search($request->all());
        return view('admin.showroom.showroom_category.index', compact('dsshowroom',  'countDeleted', 'config', 'products'));
    }
    }
    public function create()
{
    return view('admin.showroom.showroom_add.index'); // Trả về view tạo showroom
}

public function store(Request $request)
{
    // Xác thực dữ liệu đầu vào
    $request->validate([
        'name' => 'required|string|max:125',
        'address' => 'nullable|string|max:225',
        'phone' => 'nullable|string|max:30',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    // Kiểm tra nếu name chứa từ "kho" (không phân biệt hoa thường) và showroom "Kho" đã tồn tại
    $nameLower = Str::lower($request->name); // Chuyển name về dạng chữ thường để kiểm tra
    if (Str::contains($nameLower, 'kho') && Showroom::whereRaw("LOWER(name) LIKE '%kho%'")->exists()) {
        toastr()->error('Không thể thêm vì showroom liên quan đến "Kho" đã tồn tại trên hệ thống.');
        return redirect()->route('showroomcategory.index');
    }

    // Tạo một showroom mới
    $showroom = new Showroom();
    $showroom->name = $request->name;
    $showroom->address = $request->address;
    $showroom->phone = $request->phone;

    // Xử lý upload hình ảnh
    if ($request->hasFile('image')) {
        $image = $request->file('image');
        $imageName = time() . '_' . $image->getClientOriginalName();
        $image->move(public_path('uploads/showrooms'), $imageName);
        $showroom->image = 'uploads/showrooms/' . $imageName;
    }

    // Đặt publish thành 4 nếu name chứa từ liên quan đến "kho", ngược lại đặt là 2
    $showroom->publish = Str::contains($nameLower, 'kho') ? 4 : 2;

    $showroom->save(); // Lưu showroom vào cơ sở dữ liệu
    toastr()->success('Thêm danh mục thành công!');
    return redirect()->route('showroomcategory.index');
}



    public function edit($id)
{
    $showroom = Showroom::findOrFail($id);
    return view('admin.showroom.showroom_edit.index', compact('showroom'));
}

public function update(Request $request, $id)
{
    // Validate dữ liệu từ request
    $request->validate([
        'name' => 'required|string|max:125',
        'address' => 'nullable|string|max:225',
        'phone' => 'nullable|string|max:30',
        'image' => 'nullable|image|max:2048', // Kiểm tra ảnh nếu có
    ]);

    // Tìm showroom theo id
    $showroom = Showroom::findOrFail($id);
    $showroom->name = $request->name;
    $showroom->address = $request->address;
    $showroom->phone = $request->phone;

    // Nếu có upload hình ảnh mới
    if ($request->hasFile('image')) {
        // Kiểm tra xem showroom có hình ảnh cũ hay không và xóa nếu cần
        if ($showroom->image) {
            $oldImagePath = public_path($showroom->image);
            if (file_exists($oldImagePath)) {
                unlink($oldImagePath); // Xóa hình ảnh cũ
            }
        }
        // Upload hình ảnh mới và lưu vào đúng thư mục
        $image = $request->file('image');
        $imageName = time() . '_' . $image->getClientOriginalName(); // Đặt tên file
        $image->move(public_path('uploads/showrooms'), $imageName); // Di chuyển file đến thư mục public/uploads/showrooms
        $showroom->image = 'uploads/showrooms/' . $imageName; // Lưu đường dẫn tương đối của ảnh
    }

    // Lưu showroom
    $showroom->save();
    toastr()->success('Hoàn tất thay đổi!');
    // Redirect về trang danh sách showroom và hiển thị thông báo thành công
    return redirect()->route('showroomcategory.index');
}

public function togglePublish($id, Request $request)
{
    $showroom = Showroom::findOrFail($id); // Tìm showroom theo ID

    // Lấy giá trị publish từ checkbox
    $showroom->publish = $request->has('publish') ? 2 : 1; // Nếu checked thì publish = 2, ngược lại = 1
    $showroom->save(); // Lưu thay đổi vào cơ sở dữ liệu

    return redirect()->route('showroomcategory.index')->with('success', 'Trạng thái publish đã được cập nhật.');
}
public function restore(string $id)
{
    $showroom = Showroom::onlyTrashed()->find($id);

    if (!$showroom) {
        return redirect()->back()->withErrors(['Showroom không tồn tại!']);
    } else {
        $showroom->publish = 2; // Đặt trạng thái publish về 2 (hoạt động)
        $showroom->save(); // Lưu thay đổi
        $showroom->restore(); // Khôi phục showroom
        toastr()->success('Khôi phục thành công!');
        return redirect()->back();
    }
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



public function showAddProductForm($showroomId)
{
    $showroom = Showroom::findOrFail($showroomId); // Tìm showroom
    $products = Product::all(); // Lấy tất cả sản phẩm từ database

    // Trả về view và truyền dữ liệu products vào form
    return view('admin.showroom.showroom_add.add_product', compact('showroom', 'products'));
}

public function addProductToShowroom(Request $request)
{
    // Validate yêu cầu, bao gồm kiểm tra product_id, showroom_id và stock
    $request->validate([
        'product_id' => 'required|exists:products,id',
        'showroom_id' => 'required|exists:showrooms,id', // Xác thực showroom_id
        'stock' => 'required|integer|min:1', // Xác thực stock phải là số nguyên >= 1
    ]);

    // Tìm showroom theo showroom_id
    $showroom = Showroom::findOrFail($request->showroom_id);

    // Kiểm tra nếu sản phẩm đã tồn tại trong showroom
    if (!$showroom->products()->where('product_id', $request->product_id)->exists()) {
        // Nếu chưa có, thêm sản phẩm vào showroom với số lượng (stock)
        $showroom->products()->attach($request->product_id, ['stock' => $request->stock]);
        
        // Trả về phản hồi thành công
        return response()->json(['success' => true, 'message' => 'Sản phẩm đã được thêm vào showroom với số lượng ' . $request->stock . '!']);
    } else {
        // Trả về phản hồi không thành công
        return response()->json(['success' => false, 'message' => 'Sản phẩm đã tồn tại trong showroom này.']);
    }
}
}
