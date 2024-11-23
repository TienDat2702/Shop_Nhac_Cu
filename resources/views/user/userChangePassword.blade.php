@extends('user.layouts.app')
@section('title', 'Thay đổi mật khẩu')
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
    .text-danger {
        color: red !important;
    }
</style>

<main class="pt-90">
    <section class="my-account container">
        <h2 class="page-title">Thay đổi mật khẩu</h2>
        <div class="row">
            <div class="col-lg-2">
                <ul class="account-nav">
                    @include('user.layouts.component.sidebarUser')
                </ul>
            </div>

            <div class="col-lg-10">
                <div class="page-content my-account__edit">
                    <div class="my-account__edit-form">
                        <form name="change_password_form" action="{{ route('customer.check_change_password') }}" method="POST" class="needs-validation" novalidate="">
                            @csrf
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-floating my-3">
                                        <input type="password" class="form-control" placeholder="Mật khẩu hiện tại" name="current_password" required="">
                                        <label for="current_password">Mật khẩu hiện tại</label>
                                        @error('current_password')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-floating my-3">
                                        <input type="password" class="form-control" placeholder="Mật khẩu mới" name="new_password" required="">
                                        <label for="new_password">Mật khẩu mới</label>
                                        @error('new_password')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-floating my-3">
                                        <input type="password" class="form-control" placeholder="Xác nhận mật khẩu mới" name="new_password_confirmation" required="">
                                        <label for="new_password_confirmation">Xác nhận mật khẩu mới</label>
                                        @error('new_password_confirmation')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="my-3">
                                        <button type="submit" class="btn btn-primary">Thay đổi mật khẩu</button>
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
