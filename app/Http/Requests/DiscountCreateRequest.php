<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DiscountCreateRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:50|unique:discounts,name',
            'discount_rate' => 'required|numeric|min:0|max:100',
            'max_value' => 'required|numeric|min:0',
            'use_limit' => 'nullable|integer|min:0',
            'minimum_order_value' => 'nullable|numeric|min:0',
            'minimum_total_value' => 'nullable|numeric|min:0',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Bạn chưa nhập mã giảm giá',
            'name.exists' => 'Mã giảm giá không tồn tại',
            'discount_rate.required' => 'Bạn chưa nhập tỷ lệ giảm giá',
            'discount_rate.numeric' => 'Tỷ lệ giảm giá phải là số',
            'discount_rate.min' => 'Tỷ lệ giảm giá phải lớn hơn hoặc bằng 0',
            'discount_rate.max' => 'Tỷ lệ giảm giá không được vượt quá 100%',
            'max_value.required' => 'Bạn chưa nhập giá trị giảm tối đa',
            'max_value.numeric' => 'Giá trị giảm tối đa phải là số',
            'max_value.min' => 'Giá trị giảm tối đa phải lớn hơn hoặc bằng 0',
            'start_date.required' => 'Bạn chưa nhập ngày bắt đầu',
            'start_date.date' => 'Ngày bắt đầu không hợp lệ',
            'end_date.required' => 'Bạn chưa nhập ngày kết thúc',
            'end_date.date' => 'Ngày kết thúc không hợp lệ',
            'end_date.after' => 'Ngày kết thúc phải sau ngày bắt đầu',
            'use_limit.integer' => 'Giới hạn sử dụng phải là số nguyên',
            'use_limit.min' => 'Giới hạn sử dụng phải lớn hơn hoặc bằng 0',
        ];
    }
}
