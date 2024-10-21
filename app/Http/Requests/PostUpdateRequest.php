<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PostUpdateRequest extends FormRequest
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
            'title' => 'required|max:125',
            'slug' => 'required|unique:posts,slug,'.$this->id.'|max:125',
        ];
    }
    public function messages()
    {
        return [
            'title.required' => 'Bạn chưa nhập tiêu đề',
            'title.max' => 'Tiêu đề không được vượt quá 125 từ',
            // 'title.unique' => 'Tiêu đề danh mục đã được xử dụng',
            'slug.required' => 'Bạn chưa nhập đường đãn',
            'slug.max' => 'Đường dẫn không được vượt quá 125 từ',
            'slug.unique' => 'Đường dẫn danh mục đã được xử dụng',
        ];
    }
}
