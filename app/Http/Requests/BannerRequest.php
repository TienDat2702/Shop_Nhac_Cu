<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BannerRequest extends FormRequest
{
    /**
     * Xác định quyền của người dùng để thực hiện request này.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Quy tắc xác thực áp dụng cho request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Một file ảnh
            'order' => 'required|integer|min:1', // Giá trị thứ tự phải là số nguyên >= 1
            'position' => 'required|integer|min:1', // Giá trị vị trí phải là số nguyên >= 1
            'title' => 'required|string|max:255', // Tiêu đề
            'strong_title' => 'required|string|max:255', // Tiêu đề mạnh
            'description' => 'nullable|string|max:500', // Mô tả (không bắt buộc)
        ];
    }

    /**
     * Tùy chỉnh thông báo lỗi.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'image.required' => 'Vui lòng tải lên một hình ảnh.',
            'image.image' => 'File tải lên phải là hình ảnh.',
            'image.mimes' => 'Hình ảnh phải có định dạng: jpeg, png, jpg, gif.',
            'image.max' => 'Kích thước hình ảnh không được vượt quá 2MB.',
            'order.required' => 'Vui lòng nhập thứ tự.',
            'order.integer' => 'Thứ tự phải là một số nguyên.',
            'order.min' => 'Thứ tự phải lớn hơn hoặc bằng 1.',
            'position.required' => 'Vui lòng nhập vị trí.',
            'position.integer' => 'Vị trí phải là một số nguyên.',
            'position.min' => 'Vị trí phải lớn hơn hoặc bằng 1.',
            'title.required' => 'Vui lòng nhập tiêu đề.',
            'title.string' => 'Tiêu đề phải là chuỗi ký tự.',
            'title.max' => 'Tiêu đề không được vượt quá 255 ký tự.',
            'strong_title.required' => 'Vui lòng nhập tiêu đề mạnh.',
            'strong_title.string' => 'Tiêu đề mạnh phải là chuỗi ký tự.',
            'strong_title.max' => 'Tiêu đề mạnh không được vượt quá 255 ký tự.',
            'description.string' => 'Mô tả phải là chuỗi ký tự.',
            'description.max' => 'Mô tả không được vượt quá 500 ký tự.',
        ];
    }
}
