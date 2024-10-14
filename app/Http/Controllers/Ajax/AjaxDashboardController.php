<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AjaxDashboardController extends Controller
{
    public function changeStatus(Request $request)
    {
        //$request->model, $request->id, $request->value lấy từ option bên ajax

        // Đảm bảo bạn có import model đúng cách
        $modelClass = 'App\Models\\' . ucfirst($request->model); // đường dẫn đến model ví dụ App\Models\Post
        $item = $modelClass::find($request->id); // tìm id, Post::findFirst

        if ($item) { // nếu tồn tại
            $item->publish = $request->value; // Cập nhật trạng thái
            $item->save(); // lưu trạng thái
            return response()->json(['flag' => true]); // trả về kết quả Json true
        }

        return response()->json(['flag' => false]);
    }
}
