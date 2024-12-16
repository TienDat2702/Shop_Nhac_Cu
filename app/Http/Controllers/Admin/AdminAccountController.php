<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserCreateRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use App\Services\UploadImageService;

class AdminAccountController extends Controller
{
    protected $uploadImageService;

    public function __construct(UploadImageService $uploadImageService)
    {
        $this->uploadImageService = $uploadImageService;
    }

    //--------------------------------- Hiển thị danh sách người dùng ----------------------------------------
    
    public function index(Request $request)
    {
        $date = User::Date();
        $countDeleted = User::onlyTrashed()->get();

        if ($request['deleted'] == 'daxoa') {
            $config = 'deleted';
            $getDeleted = User::onlyTrashed()->Search($request->all());
            return view('admin.users.user.index', compact('config', 'countDeleted', 'getDeleted', 'date'));
        } else {
            $config = 'index';
            $users = User::GetUserAll()->Search($request->all());
            return view('admin.users.user.index', compact('users', 'countDeleted', 'config', 'date'));
        }
    }

    //--------------------------------- Hiện thêm người dùng ----------------------------------------

    public function create()
    {
        $roles = Role::all(); 
        return view('admin.users.user.create', compact('roles'));

    }

    //--------------------------------- Xử lý thêm người dùng ----------------------------------------

    public function store(UserCreateRequest $request)
    {
       
        $user = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'phone' => $request->input('phone'),
            'address' => $request->input('address'),
            'password' => bcrypt($request->input('password')),
            'role_id' => $request->input('role_id'),
            'publish' => $request->input('publish', 1),
        ]);
        $uploadPath = public_path( 'uploads/users/');
        $this->uploadImageService->uploadImage($request, $user, $uploadPath);

        if ($user) {
            toastr()->success('Thêm người dùng mới thành công!');
        } else {
            toastr()->error('Thêm người dùng mới không thành công.');
        }

        return redirect()->route('user.index');
    }

    //--------------------------------- Hiển thị chỉnh sửa người dùng ----------------------------------------

    public function edit(string $id)
    {
        $user = User::findOrFail($id);
        $roles = Role::all(); // Lấy tất cả vai trò từ bảng roles
        return view('admin.users.user.update', compact('user', 'roles'));
        
    }

    //--------------------------------- Xử lý chỉnh sửa người dùng ----------------------------------------

    public function update(UserUpdateRequest $request, string $id)
    {
        $user = User::findOrFail($id);
    
        $data = [
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'phone' => $request->input('phone'),
            'address' => $request->input('address'),
            'role_id' => $request->input('role_id'),
            'publish' => $request->input('publish', 1),
        ];
    
        if ($request->filled('password')) {
            $data['password'] = bcrypt($request->input('password'));
        }
    
        // Kiểm tra và xử lý ảnh upload
        if ($request->hasFile('image')) {
            $uploadedImage = $request->file('image');
    
            if ($uploadedImage->isValid()) {
                // Xóa ảnh cũ nếu tồn tại
                if ($user->image) {
                    $oldImagePath = public_path('uploads/users/' . $user->image);
                    if (file_exists($oldImagePath)) {
                        unlink($oldImagePath);
                    }
                }
    
                // Lưu ảnh mới
                $uploadPath = public_path('uploads/users/');
                $imageName = time() . '_' . $uploadedImage->getClientOriginalName();
                $uploadedImage->move($uploadPath, $imageName);
                $data['image'] = $imageName; // Cập nhật tên ảnh vào cơ sở dữ liệu
            } else {
                return back()->withErrors(['Tệp ảnh không hợp lệ hoặc bị lỗi trong quá trình tải lên.']);
            }
        }
        
        // Cập nhật dữ liệu người dùng
        if ($user->update($data)) {
            toastr()->success('Cập nhật người dùng thành công!');
        } else {
            toastr()->error('Cập nhật người dùng không thành công.');
        }
        // dd($user);
    
        return redirect()->route('user.index');
    }
    


    //--------------------------------- Xử lý xóa mềm người dùng ----------------------------------------

    public function destroy(string $id)
    {
        $user = User::findOrFail($id);

        // Xóa ảnh nếu tồn tại
        if ($user->image) {
            $imagePath = public_path('uploads/users' . $user->image);
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }

        if ($user->delete()) {
            toastr()->success('Xóa người dùng thành công!');
        } else {
            toastr()->error('Xóa người dùng không thành công!');
        }
        return redirect()->back();
    }

    //--------------------------------- Khôi phục người dùng ----------------------------------------

    public function restore(string $id)
    {
        $user = User::onlyTrashed()->findOrFail($id);

        if ($user->restore()) {
            toastr()->success('Khôi phục người dùng thành công!');
        } else {
            toastr()->error('Khôi phục người dùng không thành công!');
        }
        return redirect()->back();
    }

    //--------------------------------- Xóa vĩnh viễn người dùng ----------------------------------------

    public function forceDelete(string $id)
    {
        $user = User::onlyTrashed()->findOrFail($id);

        // Xóa ảnh nếu tồn tại
        if ($user->image) {
            $imagePath = public_path('uploads/users' . $user->image);
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }

        if ($user->forceDelete()) {
            toastr()->success('Xóa vĩnh viễn người dùng thành công!');
        } else {
            toastr()->error('Xóa vĩnh viễn người dùng không thành công!');
        }
        return redirect()->back();
    }
}
