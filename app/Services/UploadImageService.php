<?php

namespace App\Services;

use App\Services\Interfaces\IUploadImageService;

/**
 * Class UploadImageService
 * @package App\Services
 */
class UploadImageService
{
    public function uploadImage($request, $model, $uploadPath){
        if($request->hasFile('image')){
            $file = $request->file('image');
            
            $fileName = time() . '.' . $file->getClientOriginalExtension();

            // $uploadPath = public_path('uploads/posts/post_categories');

            $file->move($uploadPath, $fileName);

            $model->image = $fileName;
        }
        else if($request->filled('oldImage')) {
            $base64Image = $request->input('oldImage');
            // if($base64Image ==  $model->image){
            //     return $model->image;
            // }
            $fileName = time() . '.jpg'; // Tạo tên file duy nhất
            // $uploadPath = public_path('uploads/posts/post_categories');
            
            // Tách base64 và chuyển đổi thành file ảnh
            //: Chuỗi base64 có dạng data:image/jpeg;base64,<mã-base64>
            list($type, $base64Image) = explode(';', $base64Image); // $base64Image =  base64,<mã-base64>
            list(, $base64Image) = explode(',', $base64Image); // $base64Image = <mã-base64>
            //Dùng base64_decode để chuyển chuỗi base64 thành dữ liệu nhị phân ảnh.
            $imageData = base64_decode($base64Image);
            // Lưu file
            file_put_contents($uploadPath . '/' . $fileName, $imageData);
            $model->image = $fileName;
        }
        return $model->save();
    }
}
