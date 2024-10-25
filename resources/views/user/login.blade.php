@extends('user.layouts.app')
@section('content')
    <main class="pt-90">
        <div class="mb-4 pb-4"></div>
        <section class="login-register container">
            <ul class="nav nav-tabs mb-5" id="login_register" role="tablist">
                <li class="nav-item" role="presentation">
                    <a class="nav-link nav-link_underscore active" id="login-tab" data-bs-toggle="tab" href="#tab-item-login"
                        role="tab" aria-controls="tab-item-login" aria-selected="true">Login</a>
                </li>
            </ul>
            <div class="tab-content pt-2" id="login_register_tab_content">
                <div class="tab-pane fade show active" id="tab-item-login" role="tabpanel" aria-labelledby="login-tab">
                    <div class="login-form">

                        <form method="POST" action="{{ route('user.dologin') }}" name="login-form" class="needs-validation"
                            novalidate="">
                            @csrf
                            @method('POST')
                            <div class="form-floating mb-3">
                                <input class="form-control form-control_gray " name="email" value=""
                                    autocomplete="email" autofocus="" type="text">
                                <label for="email">Email address *</label>
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
                                <label for="customerPasswodInput">Password *</label>
                                @if ($errors->any())
                                <span class="error-message"> *
                                    {{
                                        $errors->first('password')
                                    }}
                                </span>
                            @endif
                            </div>

                            <button class="btn btn-primary w-100 text-uppercase" type="submit">Log In</button>

                            <div class="customer-option mt-4 text-center">
                                <span class="text-secondary">No account yet?</span>
                                {{-- <a href="{{route('user.register')}}" class="btn-text js-show-register">Create Account</a> | <a
                                    href="my-account.html" class="btn-text js-show-register">My Account</a> --}}
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection
