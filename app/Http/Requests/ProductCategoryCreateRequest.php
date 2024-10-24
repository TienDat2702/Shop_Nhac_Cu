<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductCategoryCreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|unique:product_categories|max:225', // Đã cập nhật tên bảng
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Bạn chưa nhập tiêu đề',
            'name.max' => 'Tiêu đề không được vượt quá 225 ký tự', // Chỉnh sửa từ "từ" thành "ký tự"
            'name.unique' => 'Tiêu đề danh mục đã được sử dụng', // Chỉnh sửa từ "xử dụng" thành "sử dụng"
        ];
    }
}
