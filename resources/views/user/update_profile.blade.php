@extends('user.layouts.app')
@section('title', 'Cập nhật thông tin cá nhân')
@section('content')

<style>
    .form-floating {
        position: relative;
        margin-bottom: 1.5rem;
    }
    .form-floating input {
        height: 3.5rem;
        padding: 1rem;
        border: 1px solid #ccc;
        border-radius: 0.5rem;
        width: 100%;
        box-sizing: border-box;
        transition: border-color 0.3s;
    }
    .form-floating input:focus {
        border-color: #007bff;
        outline: none;
    }
    .form-floating label {
        position: absolute;
        top: 0;
        left: 0;
        padding: 1rem;
        pointer-events: none;
        transition: all 0.3s;
        color: #6c757d;
    }
    .form-floating input:focus + label,
    .form-floating input:not(:placeholder-shown) + label {
        top: -1rem;
        left: 0.75rem;
        font-size: 0.85rem;
        color: #007bff;
    }
    .btn-primary {
        background-color: #007bff;
        border: none;
        padding: 0.75rem 1.5rem;
        border-radius: 0.5rem;
        color: #fff;
        cursor: pointer;
        transition: background-color 0.3s;
    }
    .btn-primary:hover {
        background-color: #0056b3;
    }
    .text-danger{
        color: red !important;
    }
</style>

<main class="pt-90">
    <section class="my-account container">
        <h2 class="page-title">Cập nhật thông tin cá nhân</h2>
        <div class="row">
            <div class="col-lg-2">
                <ul class="account-nav">
                    @include('user.layouts.component.sidebarUser')
                </ul>
            </div>

            <div class="col-lg-10">
                <div class="page-content my-account__edit">
                    <div class="my-account__edit-form">
                        <form name="account_edit_form" action="{{ route('customer.check_update') }}" method="POST" class="needs-validation" novalidate="">
                            @csrf
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-floating my-3">
                                        <input type="text" class="form-control" placeholder="Tên đầy đủ" name="name" value="{{ old('name', $customer->name) }}" required="">
                                        <label for="name">Tên đầy đủ</label>
                                        @error('name')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-floating my-3">
                                        <input type="text" class="form-control" placeholder="Số điện thoại" name="phone" value="{{ old('phone', $customer->phone) }}" required="">
                                        <label for="phone">Số điện thoại</label>
                                        @error('phone')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-floating my-3">
                                        <input type="email" class="form-control" placeholder="Địa chỉ email" name="email" value="{{ old('email', $customer->email) }}" required="">
                                        <label for="email">Địa chỉ email</label>
                                        @error('email')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-floating my-3">
                                        <input type="text" class="form-control" placeholder="Địa chỉ" name="address" value="{{ old('address', $customer->address) }}" required="">
                                        <label for="address">Địa chỉ</label>
                                        @error('address')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-floating my-3">
                                        <input type="password" class="form-control" placeholder="Mật khẩu" name="password" required="">
                                        <label for="password">Mật khẩu</label>
                                        @error('password')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="my-3">
                                        <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </section>
</main>
@endsection
