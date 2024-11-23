@extends('user.layouts.App_Use_Login_Ad')
@section('content')
<main class="pt-90">
    <div class="mb-4 pb-4"></div>
    <section class="login-register container">
        <ul class="nav nav-tabs mb-5" id="login_register" role="tablist">
            <li class="nav-item" role="presentation">
                <a class="nav-link nav-link_underscore active" id="forgot-tab" data-bs-toggle="tab" href="#tab-item-forgot"
                    role="tab" aria-controls="tab-item-forgot" aria-selected="true">Quên mật khẩu</a>
            </li>
        </ul>
        <div class="tab-content pt-2" id="login_register_tab_content">
            <div class="tab-pane fade show active" id="tab-item-forgot" role="tabpanel" aria-labelledby="forgot-tab">
                <div class="login-form">
                    <form method="POST" action="{{ route('admin.check_forgot') }}" name="forgot-form" class="needs-validation"
                        novalidate="">
                        @csrf
                        @method('POST')
                        <div class="form-floating mb-3">
                            <input class="form-control form-control_gray" name="email" value=""
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

                        <div class="admin-option mt-4 text-center">
                            <a href="{{route('admin.login')}}" class="btn-text js-show-login">Đăng nhập</a> 
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</main>
@endsection
