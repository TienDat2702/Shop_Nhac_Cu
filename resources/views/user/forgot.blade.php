@extends('user.layouts.app')
@section('content')
    <main class="pt-135">
        <div class="mb-4 pb-4"></div>
        <section class="login-register container">
            <ul class="nav nav-tabs mb-5" id="login_register" role="tablist">
                <li class="nav-item" role="presentation">
                    <a class="nav-link nav-link_underscore active" id="login-tab" data-bs-toggle="tab" href="#tab-item-login"
                        role="tab" aria-controls="tab-item-login" aria-selected="true">Quên mật khẩu</a>
                </li>
            </ul>
            <div class="tab-content pt-2" id="login_register_tab_content">
                <div class="tab-pane fade show active" id="tab-item-login" role="tabpanel" aria-labelledby="login-tab">
                    <div class="login-form">
                        <form method="POST" action="{{ route('customer.check_forgot') }}" name="login-form" class="needs-validation"
                            novalidate="">
                            @csrf
                            @method('POST')
                            <div class="form-floating mb-3">
                                <input class="form-control form-control_gray " name="email" value=""
                                    autocomplete="email" autofocus="" type="text">
                                <label for="email">Địa chỉ mail *</label>
                                @if ($errors->any())
                                    <span class="error-message"> *
                                        {{
                                            $errors->first('email')
                                        }}
                                    </span>
                                @endif
                            </div>

                            <div class="pb-3"></div>

                            

                            <button class="btn btn-primary w-100 text-uppercase" type="submit">Gửi mail</button>

                            <div class="customer-option mt-4 text-center">
                                
                                <a href="{{route('customer.register')}}" class="btn-text js-show-register">Tạo tài khoản</a> |
                                <a href="{{route('customer.login')}}" class="btn-text js-show-register">Đăng nhập</a> 
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection
