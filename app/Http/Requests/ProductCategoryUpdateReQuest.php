<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductCategoryUpdateRequest extends FormRequest
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
            'name' => 'required|string|max:125',
            'image' => 'nullable|mimes:jpg,jpeg,png,gif,webp|max:2048',
        ];
    }
    

    public function messages(): array
    {
        return [
            'name.required' => 'Tên danh mục là bắt buộc.',
            'name.string' => 'Tên danh mục phải là một chuỗi.',
            'name.max' => 'Tên danh mục không được vượt quá 125 ký tự.',
            
            'image.mimes' => 'Hình ảnh phải có định dạng jpg, jpeg, png, gif hoặc webp.',
            'image.max' => 'Kích thước hình ảnh không được vượt quá 2 MB.',
        ];
    }
}
