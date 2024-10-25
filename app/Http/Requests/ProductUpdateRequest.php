<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|unique:products,name,' . $this->id . '|max:125',
            // 'category_id' => 'exists:product_categories,id',
            // 'brand_id' => 'exists:brands,id',
            'price' => 'required|numeric|min:0',
            'price_sale' => 'nullable|numeric|min:0|lt:price',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Bạn chưa nhập tên sản phẩm',
            'name.max' => 'Tên sản phẩm không được vượt quá 125 ký tự',
            'name.unique' => 'Tên sản phẩm đã được sử dụng',
            'price.required' => 'Bạn chưa nhập giá sản phẩm',
            'price.numeric' => 'Giá sản phẩm phải là số',
            'price.min' => 'Giá sản phẩm phải lớn hơn hoặc bằng 0',
            'price_sale.min' => 'Giá khuyến mãi phải lớn hơn hoặc bằng 0',
            'price_sale.lt' => 'Giá khuyến mãi phải nhỏ hơn giá gốc',
        ];
    }
}
