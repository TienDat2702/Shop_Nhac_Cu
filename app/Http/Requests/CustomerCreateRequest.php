<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CustomerCreateRequest extends FormRequest
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
            'name' => 'required|max:125',
            'email' => 'required|email|unique:customers,email|max:125',
            'phone' => 'required|max:50',
            'password' => 'required|min:6|max:50',
            'address' => 'required|max:255',
            'loyalty_level_id' => 'nullable|exists:loyalty_levels,id', // ID phải tồn tại trong bảng loyalty_levels
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Bạn chưa nhập tên khách hàng',
            'name.max' => 'Tên khách hàng không được vượt quá 125 ký tự',
            'email.required' => 'Bạn chưa nhập email',
            'email.email' => 'Email không hợp lệ',
            'email.unique' => 'Email đã được sử dụng',
            'email.max' => 'Email không được vượt quá 125 ký tự',
            'phone.required' => 'Bạn chưa nhập số điện thoại',
            'phone.max' => 'Số điện thoại không được vượt quá 50 ký tự',
            'password.required' => 'Bạn chưa nhập mật khẩu',
            'password.min' => 'Mật khẩu phải có ít nhất 6 ký tự',
            'password.max' => 'Mật khẩu không được vượt quá 50 ký tự',
            'address.required' => 'Bạn chưa nhập địa chỉ',
            'address.max' => 'Địa chỉ không được vượt quá 255 ký tự',
            'loyalty_level_id.exists' => 'Cấp độ trung thành không hợp lệ',
        ];
    }
}
