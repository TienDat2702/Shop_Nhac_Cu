@extends('user.layouts.app')
@section('title', 'Đăng nhập')
@section('content')
    <main class="pt-90">
        <div class="mb-4 pb-4"></div>
        <section class="login-register container">
            <ul class="nav nav-tabs mb-5" id="login_register" role="tablist">
                <li class="nav-item" role="presentation">
                    <a class="nav-link nav-link_underscore active" id="login-tab" data-bs-toggle="tab" href="#tab-item-login"
                        role="tab" aria-controls="tab-item-login" aria-selected="true">Đăng nhập</a>
                </li>
            </ul>
            <div class="tab-content pt-2" id="login_register_tab_content">
                <div class="tab-pane fade show active" id="tab-item-login" role="tabpanel" aria-labelledby="login-tab">
                    <div class="login-form">
                        <form method="POST" action="{{ route('customer.dologin') }}" name="login-form" class="needs-validation"
                            novalidate="">
                            @csrf
                            @method('POST')
                            <div class="form-floating mb-3">
                                <input class="form-control form-control_gray " name="email" value=""
                                    autocomplete="email" autofocus="" type="text">
                                <label for="email">Địa chỉ Mail *</label>
                                @if ($errors->any())
                                    <span class="error-message"> *
                                        {{
                                            $errors->first('email')
                                        }}
                                    </span>
                                @endif
                            </div>

                            <div class="pb-3"></div>

                            <div class="form-floating mb-3">
                                <input id="password" type="password" class="form-control form-control_gray "
                                    name="password" autocomplete="current-password">
                                <label for="customerPasswodInput">Mật khẩu *</label>
                                @if ($errors->any())
                                <span class="error-message"> *
                                    {{
                                        $errors->first('password')
                                    }}
                                </span>
                            @endif
                            </div>
                            <!-- Thẻ input ẩn để lưu giá trị từ localStorage -->
                            <input type="hidden" class="input_login_redirect" name="last_route" id="last_route">
                            <input type="hidden" class="input_slug_redirect" name="last_slug" value="" />
                            <button class="btn btn-primary w-100 text-uppercase btn_login_redirect" type="submit">Đăng nhập</button>

                            <div class="customer-option mt-4 text-center">
                                <span class="text-secondary">Bạn chưa có tài khoản ?</span>
                                <a href="{{route('customer.register')}}" class="btn-text js-show-register">Tạo tài khoản</a> |
                                <a href="{{route('customer.forgot')}}" class="btn-text js-show-register">Quên mật khẩu</a> 
                            </div>
                        </form>
                       
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection
