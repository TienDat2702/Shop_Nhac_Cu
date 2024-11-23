<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Showroom;
use App\Models\Banner;

class UserShowroomController extends Controller
{
    public function showMap()
    {
        $banners = Banner::where('position', 1)
                 ->where('publish', 2)
                 ->get();
        // Lấy danh sách showroom từ cơ sở dữ liệu
        $showrooms = Showroom::select('name', 'address', 'latitude', 'longitude')->get();

        // Trả dữ liệu về view
        return view('user.showroom', compact('showrooms', 'banners'));
    }

}
