<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\User;

class UserUpdateRequest extends FormRequest
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
        $userId = $this->route('id'); // Lấy ID của user từ route
        return [
            'name' => 'required|max:125',
            'email' => 'required|email|unique:users,email,' . $userId . '|max:125',
            'phone' => 'required|max:50',
            'password' => 'nullable|min:6|max:50',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Bạn chưa nhập tên người dùng',
            'name.max' => 'Tên người dùng không được vượt quá 125 ký tự',
            'email.required' => 'Bạn chưa nhập email',
            'email.email' => 'Email không hợp lệ',
            'email.unique' => 'Email đã được sử dụng',
            'email.max' => 'Email không được vượt quá 125 ký tự',
            'phone.required' => 'Bạn chưa nhập số điện thoại',
            'phone.max' => 'Số điện thoại không được vượt quá 50 ký tự',
            'password.min' => 'Mật khẩu phải có ít nhất 6 ký tự',
            'password.max' => 'Mật khẩu không được vượt quá 50 ký tự',
        ];
    }
}
