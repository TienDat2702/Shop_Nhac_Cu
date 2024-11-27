<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ShowroomUpdateRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Cho phép request này chạy
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:125',
            'address' => 'nullable|string|max:225',
            'phone' => 'nullable|string|max:30',
            'image' => 'nullable|image|max:2048',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Bạn chưa nhập tên showroom',
            'address.required' => 'Bạn chưa nhập địa chỉ',
            'phone.required' => 'Bạn chưa nhập số điện thoại',
            'image.required' => 'Bạn chưa nhập tên hình ảnh',
            'name.max' => 'Tên showroom không được vượt quá 125 ký tự',
            'name.unique' => 'Tên showroom đã được sử dụng',
            'address.max' => 'Địa chỉ không được vượt quá 225 ký tự',
            'phone.max' => 'Số điện thoại không được vượt quá 30 ký tự',
            'image.image' => 'Tệp phải là hình ảnh',
            'image.mimes' => 'Hình ảnh phải có định dạng jpeg, png, jpg hoặc gif',
            'image.max' => 'Hình ảnh không được vượt quá 2048 KB',
        ];
    }
}
